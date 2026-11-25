# Design Direction

## Identity
- Product: Nusantara Build Construction, a professional construction company website.
- Tone: confident, grounded, industrial-professional.

## Palette
- Core: navy (`#0b1220`, `#111a2e`) for structure/trust, steel neutrals (`#f3f5f7`, `#e5e9ee`, `#6a707e`, `#374151`) for content surfaces.
- Accent: amber (`#f59e0b`, `#b36204`) for calls to action and key highlights; used sparingly on buttons, active states, and key labels.
- Reason: navy/steel conveys construction reliability; amber references safety/high-visibility equipment while providing clear focus.

## Typography
- Display: Barlow Condensed for headings/labels; reason: condensed industrial character fits construction branding and creates clear hierarchy.
- Body: Manrope for readable UI/content text; reason: neutral, legible sans for long-form and interface copy.

## Dials
Dial: ENERGY 2 / RHYTHM 2 / MOTION 2

## Major Decisions
- Color: navy+steel+amber palette supports brand trust and clear CTA hierarchy.
- Layout: content-led sections (hero, company intro, services, projects, stats, value props, testimonials, CTA) follow the company story rather than decorative filler.
- Typography: condensed display + humanist sans separates headings from body while staying readable.
- Spacing: Tailwind section/container rhythm (`section-pad`, `container-site`) keeps consistent vertical pacing with selective variation.
- Cards: used only where content is naturally repeatable (services, projects, testimonials, stats); feature/value cards reflect distinct value propositions.
- Icons: service/feature icons map to content meaning; no generic AI glyphs.
- Motion: reveal/stagger/counter/hero transitions guide attention and provide feedback; reduced-motion is respected.

## Component Decisions
- `sections/hero`: split layout with image overlay for immediate brand/context; highlight stats pulled from live statistics to avoid duplicated claims.
- `sections/company-intro`: image + copy split explains who the company is before listing capabilities.
- `sections/services` + `cards/service-card`: repeatable service catalog uses cards; icon chosen by service key, CTA goes to service detail.
- `sections/featured-projects` + `cards/project-card`: portfolio proof uses image cards with category/location metadata and detail links.
- `sections/stats`: numeric proof separated into its own band for scanability; values come from managed statistics.
- `sections/why-choose-us` + `cards/feature-card`: four value propositions use distinct icons to differentiate meaning, not decoration.
- `sections/testimonials`: carousel presents client feedback; copy states feedback source without unverifiable trust claims.
- `sections/cta`: high-contrast accent band marks the primary conversion action at page end.
- `layout/navbar` + `layout/footer`: persistent navigation and contact/social destinations reflect real routes/data only.
- `ui/*`: shared button/form/accordion/heading primitives keep interaction, focus, and spacing consistent.
