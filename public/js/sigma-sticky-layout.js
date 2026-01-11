(() => {
  const ROOT = document.documentElement;
  const APP_HEADER_SELECTOR = ".sigma-app-header";
  const TOOLBAR_SELECTOR = ".sigma-sticky-toolbar";

  let rafId = null;

  const isElementVisible = (element) =>
    !!(element && (element.offsetParent !== null || element.getClientRects().length));

  const formatPx = (value) => `${Math.max(0, value).toFixed(2)}px`;

  const setStackVars = () => {
    const header = document.querySelector(APP_HEADER_SELECTOR);
    if (isElementVisible(header)) {
      ROOT.style.setProperty(
        "--sigma-app-header-offset",
        formatPx(header.getBoundingClientRect().height)
      );
    } else {
      ROOT.style.removeProperty("--sigma-app-header-offset");
    }

    const toolbars = Array.from(document.querySelectorAll(TOOLBAR_SELECTOR));
    let totalHeight = 0;

    for (const toolbar of toolbars) {
      if (!isElementVisible(toolbar)) continue;
      totalHeight += toolbar.getBoundingClientRect().height;
    }

    ROOT.style.setProperty(
      "--sigma-sticky-toolbar-height",
      formatPx(totalHeight)
    );
  };

  const scheduleUpdate = () => {
    if (rafId !== null) return;
    rafId = window.requestAnimationFrame(() => {
      rafId = null;
      setStackVars();
    });
  };

  const init = () => {
    scheduleUpdate();

    window.addEventListener("load", scheduleUpdate, { passive: true });
    window.addEventListener("resize", scheduleUpdate, { passive: true });

    if ("ResizeObserver" in window) {
      const observer = new ResizeObserver(scheduleUpdate);
      const header = document.querySelector(APP_HEADER_SELECTOR);
      if (header) observer.observe(header);
      document.querySelectorAll(TOOLBAR_SELECTOR).forEach((el) => observer.observe(el));
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
