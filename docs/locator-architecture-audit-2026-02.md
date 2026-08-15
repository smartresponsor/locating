# Locator — глубокий технический аудит (архитектура, качество, CI/CD, данные)

## 1) Executive summary

Проект содержит большой объем кода и тестов, но выглядит как **сборный snapshot из нескольких веток эволюции**, а не как единая согласованная кодовая база. Это видно по сочетанию «слоистой» структуры директорий и фрагментированной реализации (пустой Domain-слой, дубли интерфейсов, минималистичные конфиги, разный стиль кода, legacy-отчеты внутри репозитория). 

**Итоговый вердикт:**
- Сильная сторона — задел по функциональности, контрактам и наблюдаемости (OpenAPI, Grafana, набор скриптов, богатый тестовый каталог).
- Ключевой риск — **предсказуемость поставки**: текущая конфигурация тестов/CI и архитектурная консистентность не соответствуют уровню production-grade платформ уровня Stripe/Google Maps/Uber.
- Прицельный фокус на ближайший цикл: нормализация архитектурных границ, выравнивание тестовой пирамиды, формализация CI/CD и обновление «источника правды» по документации/данным.

---

## 2) Архитектурность проекта

### 2.1 Слоистость и границы

**Наблюдения:**
- Директории формально отражают многослойность (`Service`, `Service`, `Entity`, `Infrastructure`, `Contract`, `Integration`, `Message`, `Strategy`).
- При этом `src/Domain/Locator` фактически пуст (`.gitkeep`), то есть доменный слой задекларирован, но не реализован как центр бизнес-логики.
- В проекте одновременно существуют несколько «семейств» интерфейсов: `Contract/*Interface.php`, `*Interface` рядом со слоями, и дополнительные инфраструктурные интерфейсы.

**Оценка:**
- Слойность: **6/10** (структура есть, но semantic ownership размыта).
- Boundary hygiene: **4/10** (местами дубли и смешение ответственности).

### 2.2 Доменная логика, SRP, SOLID

**Наблюдения:**
- Часть классов реализована как плотные value-like объекты (`AddressData`), но есть и «entities» с сервисным поведением (`BatchService`) и упрощенной процедурной логикой.
- Контроллеры включают оркестрацию, валидацию и policy-ветки (например квотирование в `LocationAddressSuggestHttpService`), что частично нарушает SRP.
- Есть хорошая практика интерфейсной абстракции провайдеров и маршрутизации (`AddressProviderRouter`), но error handling в failover-модели чрезмерно широк (`catch \Throwable`) без классификации ошибок.

**Оценка:**
- SRP: **5/10**
- SOLID в среднем: **5/10**
- DDD-зрелость: **3/10** (домен не выделен как источник инвариантов)

### 2.3 Читаемость и современные практики

**Плюсы:** strict types, namespaces, OpenAPI-контракт, PHP 8.2 baseline.

**Минусы:**
- Непоследовательный стиль форматирования (часть файлов в одну строку, часть — стандартно).
- README и contributing сильно минималистичны для масштаба репозитория.
- Наблюдается «исторический шум» (обширные legacy report-артефакты в основном дереве).

**Оценка по сравнению с отраслью:**
- С текущей консистентностью — ближе к R&D/архивному monorepo-срезу, чем к стандартизированному enterprise template.

---

## 3) Покрытие тестами

### 3.1 Что есть

- По количеству — большой набор тестов в `tests/Locator/*` (unit-ish, integration-ish, contract и т.п.).
- Есть контрактный smoke по OpenAPI presence.
- Есть интеграционный smoke-тест (`assertTrue(true)`), который выполняет функцию заглушки, а не проверки системы.

### 3.2 Проблемы

- `phpunit.xml.dist` смотрит на `tests/Unit`, `tests/Integration`, `tests/Functional`, `tests/E2E`, тогда как фактические тесты лежат в `tests/Locator/...`.
- В результате тестовая конфигурация не синхронизирована с layout репозитория.
- Отсутствуют evidence-файлы по реальному покрытию (coverage report, quality gate thresholds, mutation score).

### 3.3 Оценка зрелости тестовой пирамиды

- Unit: **средне/выше среднего по объему**, но неоднородно по качеству.
- Integration: **частично**.
- E2E: **формально обозначено, но не подтверждено валидной конфигурацией/пайплайном**.

**Итог:** test maturity **4.5/10** (много тестовых файлов ≠ высокая надежность поставки).

---

## 4) Ошибки, слабые места, технический долг

### Критические
1. **Несогласованность test runner и структуры каталогов.**
2. **Отсутствие lock-файла зависимостей и неустойчивый dependency bootstrap.**
3. **Нет явного CI-конвейера (GitHub Actions/GitLab/Jenkins конфигов).**

### Высокие
4. **Пустой доменный слой при заявленной доменной архитектуре.**
5. **Смешение ролей Entity/Service и неодинаковый coding style.**
6. **Риск скрытых сбоев из-за broad exception swallowing в failover-путях.**

### Средние
7. Документация не отражает текущий реальный процесс сборки/релиза.
8. Legacy-артефакты в основном дереве усложняют навигацию.
9. Нет явной модели версионирования API lifecycle (deprecation policy, changelog discipline).

---

## 5) Документация (README, API, observability, infra docs)

### Что хорошо
- Есть OpenAPI v1 контракт.
- Есть отдельные docs по метрикам/наблюдаемости.
- Есть Grafana dashboard JSON.

### Что слабо
- README не содержит полноценной архитектурной карты, матрицы ответственности, SLA/SLO, релизного процесса.
- `docs/contributing.md` и `docs/env.md` слишком краткие для онбординга.
- Нет «операционной книги» production incident response (on-call playbook, runbooks).
- Нет Helm chart/Kubernetes deployment manifest как «first-class» инфраструктурных артефактов.

**Оценка документации:** **4/10**.

---

## 6) Данные: фикстуры, demo-данные, миграции, entity-модели

### Что есть
- NDJSON fixtures (`fixtures/locator-demo.ndjson`, golden fixtures в тестах).
- Demo/fixture скрипты в `tools/`.

### Разрывы
- Не обнаружен формализованный каталог миграций приложения (Doctrine/Liquibase/Flyway и т.п.).
- Нет согласованного data contract между entity-моделями и миграционным слоем.
- Не зафиксирована политика актуализации демо-данных (freshness, synthetic vs masked real, privacy).

**Оценка data governance:** **3.5/10**.

---

## 7) CI/CD и инфраструктура

### Фактическое состояние
- Есть локальные shell-скрипты для тестов/RC gate (`tools/run-tests.sh`, `tools/locator-rc-gate.sh`).
- Есть Makefile-цели `vendor/test/smoke`.
- Не найдена декларативная CI-конфигурация в стандартных местах (GitHub Actions, GitLab CI, Jenkinsfile).

### Риски
- Повторяемость build/release зависит от локальной среды и доступа к внешним репозиториям.
- Нет формального quality gate в CI (coverage threshold, static analysis gates, security scan, SBOM, license checks).
- Отсутствие инфраструктурного IaC-контура (Terraform/Helm/Kustomize) снижает воспроизводимость окружений.

**Оценка CI/CD зрелости:** **3/10**.

---

## 8) Сравнение с лидерами отрасли

Относительно зрелых платформенных команд (уровень Stripe, Booking, Uber, Cloudflare):

- **Архитектура:** у лидеров — строгий ownership домена и контрактов; здесь — структура есть, но ownership не доведен до единообразия.
- **Качество:** у лидеров — обязательные многоступенчатые quality gates; здесь — в основном локальные скрипты.
- **Документация:** у лидеров — docs-as-code + runbooks + ADR; здесь — полезные фрагменты, но нет единого operational narrative.
- **Data/infra governance:** у лидеров — миграции, observability и deployment описаны как единая система; здесь это пока фрагментировано.

---

## 9) Технические конверторы (инициативы преобразования)

### Конвертор C1 — Architecture Integrity Program
**Цель:** превратить «формальную слоистость» в фактическую.  
**Шаги:**
1. Зафиксировать canonical architecture decision records (ADR).
2. Перенести бизнес-инварианты в `Domain` + policy objects.
3. Развести DTO/Entity/Service роли и запретить смешение через linters/architecture tests.
**KPI:** снижение cross-layer violations, стабильные публичные контракты.

### Конвертор C2 — Test Reliability Pipeline
**Цель:** доверяемый test signal в CI.  
**Шаги:**
1. Привести `phpunit.xml.dist` к реальной структуре тестов.
2. Разделить smoke / unit / integration / contract / e2e jobs.
3. Добавить coverage + mutation testing для critical-path сервисов.
**KPI:** deterministic green pipeline, coverage target >= 70% core paths, flaky rate < 2%.

### Конвертор C3 — CI/CD Industrialization
**Цель:** предсказуемый релизный цикл.  
**Шаги:**
1. Ввести декларативный pipeline (например, GitHub Actions).
2. Добавить static analysis (phpstan/psalm), security scan (SCA/SAST), SBOM.
3. Формализовать release gates (versioning, changelog, artifact signing).
**KPI:** lead time ↓, deployment failure rate ↓, MTTR ↓.

### Конвертор C4 — Data & Schema Governance
**Цель:** единая дисциплина данных.  
**Шаги:**
1. Ввести миграционную систему и baseline schema.
2. Связать entity evolution с migration policies.
3. Регламентировать fixture lifecycle и data privacy checks.
**KPI:** zero-drift между моделями и schema, repeatable seed/fixture pipeline.

### Конвертор C5 — Docs & Operations Excellence
**Цель:** быстрый онбординг и снижение операционных рисков.  
**Шаги:**
1. Обновить README до «single source of truth».
2. Добавить runbooks (incident, rollback, degradation playbooks).
3. Свести API, observability, SLO и release process в единую docs-map.
**KPI:** onboarding time ↓, incident handling consistency ↑.

---

## 10) Приоритизированная дорожная карта

### 0–30 дней (stabilize)
- Исправить phpunit suite mapping.
- Ввести базовый CI pipeline (lint + tests + contract check).
- Зафиксировать dependency lock и reproducible build.

### 30–90 дней (standardize)
- Рефакторинг доменных границ и ролей классов.
- Добавить quality gates, coverage thresholds.
- Обновить README + contributing + runbook минимального уровня.

### 90–180 дней (scale)
- Полный release governance (semver, changelog automation, artifact policies).
- Расширить observability: SLO-driven alerting + postmortem template.
- Институционализировать архитектурные проверки (ADR + architecture tests).

---

## 11) Финальный вердикт

Проект имеет **высокий потенциал** и богатую функциональную основу, но текущая версия страдает от архитектурной неоднородности и недостаточной индустриализации CI/CD. 

**Рекомендуемый статус:**
- Для экспериментальной/внутренней среды: **условно готов**.
- Для mission-critical production без дополнительных мер: **не готов**.

Ключ к переходу в production-grade — последовательное выполнение конверторов C1–C5 с измеримыми KPI и регулярным архитектурным review циклом.
