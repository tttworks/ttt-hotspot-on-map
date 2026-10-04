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

## What this snippet demonstrates

This is not only a hotspot widget — it is a reference for **how far a custom Elementor
widget can be pushed**. Everything below is implemented in the 633 lines in this repository.

### 1. Elementor widget API surface covered

| Capability | How it's implemented here |
|---|---|
| Widget metadata | `get_name` / `get_title` / `get_icon` (`eicon-map-pin`) / `get_categories` / `get_keywords` |
| **Conditional asset loading** | `get_style_depends()` + `get_script_depends()` — CSS and JS load **only on pages where the widget is actually used**, never site-wide |
| Content / Style tab split | 12 control sections across 6 × `TAB_CONTENT` and 6 × `TAB_STYLE` |
| **Conditional control display** | 10 × `condition` — a control appears only when the toggle that governs it is on |
| **Responsive controls** | 8 × `add_responsive_control()` — independent desktop / tablet / mobile values |
| **Repeater** | 1 × `Controls_Manager::REPEATER` — the hotspot collection is fully user-extensible (add / remove / reorder) |
| Front-end render | `render()` emits markup, then JS re-initialises it so the **editor preview stays live** |

### 2. Control types exercised — 12 kinds

| Type | Count | What it drives in this widget |
|---|---:|---|
| `SLIDER` | **25** | X/Y coordinates, curve curvature, orbit speed, dot size, border width, radius, spacing |
| `COLOR` | **13** | marker, label, panel, border, trail, glow |
| `SELECT` | 10 | curve direction, orbit mode, ordering, speed mode, preset locations |
| `SWITCHER` | 6 | label / pulse / orbit / panel / glow toggles |
| `DIMENSIONS` | 4 | padding, margin and radius groups |
| `TEXT` / `TEXTAREA` | 3 | hotspot labels and panel copy |
| `MEDIA` | 2 | base map image, hotspot icon |
| `REPEATER` | 1 | the hotspot list itself |

### 3. Front-end behaviour (JS, 255 lines)

**Percentage positioning that survives any resize.**
Markers are stored as a percentage of the base image and converted to pixels at runtime
against the image's *rendered* rectangle — so positions hold when the container resizes,
the image scales, or the breakpoint changes. No fixed pixel coordinates anywhere.

**Bezier curve connections between hotspots.**
Hotspots can link to one another by name. The link is drawn as a curve whose **curvature
is a panel slider** (expressed in multiples of the standard arc) with a direction toggle —
i.e. real, user-controlled bezier geometry rather than a hard-coded arc.

**An orbit animation system.**
Animated markers orbit the map, driven by `requestAnimationFrame`, with:

- two motion modes plus `spoke_to_center` direction handling
- **simultaneous or sequential** scheduling, and clockwise / counter-clockwise ordering
- uniform or variable speed
- configurable dot size, glow, and **trail style / colour / width**
- explicit z-index layering (overlay `2` / marker `3` / orbit `4`) so layers never fight

**Drag-to-place inside the editor.**
In edit mode each marker becomes draggable with a *save coordinates* affordance — positions
are authored visually and written back into the control, instead of being typed as numbers.

**Editor-preview safe.**
Elementor re-renders the widget on every control change. The JS re-initialises cleanly each
time — no leaked listeners, no duplicated markers.

### 4. Styling approach (CSS, 96 lines)

- Colours, sizes and the pulse scale are written from widget settings as **CSS custom
  properties** — the stylesheet contains no hard-coded visual values
- The pulse ring is a single `@keyframes` parameterised by `--zhom-pulse-scale`
- Hover states and transitions live in CSS, not JS
- **Vanilla JS throughout — no jQuery dependency**

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
