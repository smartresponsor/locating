// Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
//
// k6 scenario for Locator SLO/chaos smoke.
// Targets:
//   - GET /locator/status
//   - GET /locator/address/suggest
//   - GET /locator/address/reverse
//
// Usage example:
//   LOCATOR_BASE_URL=http://localhost:8000 k6 run tools/locator-k6-slo-smoke.js

import http from 'k6/http';
import { check, sleep } from 'k6';

const BASE_URL = __ENV.LOCATOR_BASE_URL || 'http://localhost:8000';

export const options = {
  thresholds: {
    http_req_failed: ['rate<0.005'],      // < 0.5% errors
    http_req_duration: ['p(95)<700'],     // p95 latency < 700ms
  },
  scenarios: {
    status_fast: {
      executor: 'constant-vus',
      vus: 5,
      duration: '30s',
      exec: 'statusScenario',
    },
    suggest_load: {
      executor: 'ramping-vus',
      startVUs: 1,
      stages: [
        { duration: '30s', target: 10 },
        { duration: '60s', target: 20 },
        { duration: '30s', target: 5 },
      ],
      exec: 'suggestScenario',
    },
    reverse_spike: {
      executor: 'spike',
      vus: 0,
      startRate: 0,
      timeUnit: '1s',
      preAllocatedVUs: 10,
      maxVUs: 50,
      stages: [
        { target: 10, duration: '20s' },
        { target: 0, duration: '10s' },
      ],
      exec: 'reverseScenario',
    },
  },
};

export function statusScenario() {
  const res = http.get(`${BASE_URL}/locator/status`);
  check(res, {
    'status ok': (r) => r.status === 200,
  });
  sleep(1);
}

export function suggestScenario() {
  const queries = [
    '1600 Pennsylvania Ave NW Washington',
    '10 Downing Street London',
    'Khreshchatyk 1 Kyiv',
    'Main Street Houston',
  ];
  const query = queries[Math.floor(Math.random() * queries.length)];

  const params = {
    query: query,
    country: 'US',
    limit: 5,
    locale: 'en_US',
  };

  const url = `${BASE_URL}/locator/address/suggest?` + new URLSearchParams(params).toString();
  const res = http.get(url);

  check(res, {
    'suggest status ok': (r) => r.status === 200,
    'suggest has items': (r) => {
      try {
        const body = JSON.parse(r.body);
        return Array.isArray(body.items);
      } catch (e) {
        return false;
      }
    },
  });
}

export function reverseScenario() {
  // A small pool of test coordinates.
  const coords = [
    { lat: 38.8977, lon: -77.0365 }, // Washington
    { lat: 51.5034, lon: -0.1276 },  // London
    { lat: 50.4501, lon: 30.5234 },  // Kyiv
    { lat: 29.7604, lon: -95.3698 }, // Houston
  ];
  const coord = coords[Math.floor(Math.random() * coords.length)];

  const params = {
    lat: String(coord.lat),
    lon: String(coord.lon),
    country: 'US',
  };

  const url = `${BASE_URL}/locator/address/reverse?` + new URLSearchParams(params).toString();
  const res = http.get(url);

  check(res, {
    'reverse status ok or quota': (r) => r.status === 200 || r.status === 429,
  });
}
