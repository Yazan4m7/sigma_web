(() => {
  const ROOT = document.documentElement;
  const TOOLBAR_SELECTOR = ".sigma-sticky-toolbar";

  let rafId = null;

  const isElementVisible = (element) =>
    !!(element && (element.offsetParent !== null || element.getClientRects().length));

  const setToolbarHeightVar = () => {
    const toolbars = Array.from(document.querySelectorAll(TOOLBAR_SELECTOR));
    let totalHeight = 0;

    for (const toolbar of toolbars) {
      if (!isElementVisible(toolbar)) continue;
      totalHeight += toolbar.getBoundingClientRect().height;
    }

    ROOT.style.setProperty(
      "--sigma-sticky-toolbar-height",
      `${Math.ceil(totalHeight)}px`
    );
  };

  const scheduleUpdate = () => {
    if (rafId !== null) return;
    rafId = window.requestAnimationFrame(() => {
      rafId = null;
      setToolbarHeightVar();
    });
  };

  const init = () => {
    scheduleUpdate();

    window.addEventListener("load", scheduleUpdate, { passive: true });
    window.addEventListener("resize", scheduleUpdate, { passive: true });

    if ("ResizeObserver" in window) {
      const observer = new ResizeObserver(scheduleUpdate);
      document.querySelectorAll(TOOLBAR_SELECTOR).forEach((el) => observer.observe(el));
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

