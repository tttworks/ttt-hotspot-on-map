<p align="center">
  <img src="assets/logo.png" alt="TTTWorks" width="260">
</p>

# TTT Hotspot on Map for Elementor

**An interactive hotspot map widget for Elementor** — place markers on a custom map image,
each opening its own info panel.

---

## Demo

[![Hotspot on Map — demo](assets/demo.gif)](assets/demo.mp4)

*Click the animation for the full-quality recording → [`assets/demo.mp4`](assets/demo.mp4) (14s).*

---

## What it does

- Hotspots positioned by percentage coordinates, so the map scales cleanly at any size
- Two marker types — **center** and **edge** — with different behaviours
- Per-hotspot label, icon and content block
- Location presets plus free positioning
- Configurable marker animation

---

## Iteration record

**Twenty revisions — v1.0.0 through v1.0.20.**

This is the widget we iterated on most. Almost every revision came from using it against
real content: markers overlapping at certain viewport widths, info panels flipping off
screen near the edges, animations that looked fine in isolation but fought the page scroll.
The version history in the file header reads like a list of things you only find in production.

---

## What this repository is

**Production code, published to show how we build.**

- This is a **snippet**, not an installable plugin. No activation flow, no wp.org release.
- **No installation guide. No support. No portability guarantee.**
- Code assumes an Elementor-based WordPress stack and our own conventions.

---

## Assets

| File | Purpose |
|---|---|
| `assets/world-map.svg` | Default world map (1675×1082) used as the widget's base image |
| `assets/demo.gif` | Demo animation (embedded above) |
| `assets/demo.mp4` | Full-quality demo recording — 14s |
| `assets/logo.png` | Brand mark |

---

## License

Apache License 2.0 — permissive, **commercial use permitted**, trademark rights not granted. See [LICENSE](LICENSE).

---

**[TTTWorks](https://tttworks.com)** — Production WordPress engineering.
