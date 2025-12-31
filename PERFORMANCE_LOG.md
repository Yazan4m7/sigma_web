# Performance Log

Track load-time measurements before and after performance changes.

## How to measure
- Page: http://127.0.0.1:8000/operations-dashboard (or target page)
- Method: DevTools Performance panel or Lighthouse
- Record the primary load time in ms; note cache state (cold/warm)

## Entries
| Date | Change | Page | Method | Before (ms) | After (ms) | Notes |
|------|--------|------|--------|-------------|------------|-------|
| 2025-12-24 | Baseline measurement | /operations-dashboard | curl (5 runs, localhost) | 722.15 | n/a | Times ms: 1923.89, 396.45, 406.46, 390.15, 493.8 (first likely cold) |
| 2025-12-24 | Batch build/case loading in active builds dialog | /operations-dashboard | curl (5 runs, localhost) | 722.15 | 382.53 | After times ms: 563.08, 453.04, 372.48, 286.57, 237.52 |
| 2025-12-24 | Filter case jobs by stage in active builds dialog | /operations-dashboard | curl (5 runs, localhost) | 270.72 | 227.7 | Before times ms: 561.02, 178.42, 255.08, 177.97, 181.13; After times ms: 290.3, 209.81, 161.48, 225.1, 251.81 |
| 2025-12-25 | Device unit counting query batching | /operations-dashboard | laravel.log (Dashboard loaded in) | 333.37 | pending | Before from log: 0.33337497711182s; refresh dashboard to capture after |
