(function () {
  const signal = document.querySelector("[data-hero-signal]");
  const hero = signal ? signal.closest(".home-hero") : null;
  const animeInstance = window.anime;

  if (!signal || !hero || !animeInstance) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const coarsePointer = window.matchMedia("(pointer: coarse)");
  const dots = [];
  let frame = null;
  let resizeFrame = null;
  let width = 0;
  let height = 0;
  let targetX = 0;
  let targetY = 0;
  let cursorX = 0;
  let cursorY = 0;
  let isActive = false;
  let isVisible = true;

  const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
  const lerp = (start, end, amount) => start + (end - start) * amount;
  const smooth = (value) => {
    const safeValue = clamp(value, 0, 1);

    return safeValue * safeValue * (3 - 2 * safeValue);
  };

  const getSignalRadius = () => {
    const value = getComputedStyle(signal)
      .getPropertyValue("--hero-signal-radius")
      .trim();
    const numericValue = Number.parseFloat(value);

    if (!Number.isFinite(numericValue)) return 384;

    if (value.endsWith("rem")) {
      const rootFontSize = Number.parseFloat(
        getComputedStyle(document.documentElement).fontSize,
      );

      return numericValue * (Number.isFinite(rootFontSize) ? rootFontSize : 16);
    }

    if (value.endsWith("px")) return numericValue;

    return numericValue;
  };

  const getSettings = () => {
    const radius = getSignalRadius();

    if (width < 640) {
      return {
        maxDots: 280,
        spacing: 22,
        radius: radius * 0.42,
        innerRadius: radius * 0.32,
        noise: 30,
        wiggle: 1.6,
        interactive: false,
      };
    }

    if (width < 1024) {
      return {
        maxDots: 700,
        spacing: 18,
        radius: radius * 0.64,
        innerRadius: radius * 0.48,
        noise: 40,
        wiggle: 2.05,
        interactive: !coarsePointer.matches,
      };
    }

    return {
      maxDots: 1280,
      spacing: 16,
      radius: radius * 0.72,
      innerRadius: radius * 0.54,
      noise: 46,
      wiggle: 2.35,
      interactive: !coarsePointer.matches,
    };
  };

  const createDot = (x, y, noiseAmount) => {
    const element = document.createElement("span");
    const noiseX = (Math.random() - 0.5) * noiseAmount * 2;
    const noiseY = (Math.random() - 0.5) * noiseAmount * 2;
    const wigglePhase = Math.random() * Math.PI * 2;
    const wiggleSpeed = 1.35 + Math.random() * 1.45;
    const wiggleAmount = 0.58 + Math.random() * 1.1;

    element.className = "hero-signal__dot";
    element.style.setProperty("--dot-x", `${x}px`);
    element.style.setProperty("--dot-y", `${y}px`);
    element.style.setProperty("--noise-x", `${noiseX}px`);
    element.style.setProperty("--noise-y", `${noiseY}px`);

    signal.append(element);

    return {
      element,
      x,
      y,
      noiseX,
      noiseY,
      wigglePhase,
      wiggleSpeed,
      wiggleAmount,
      currentX: noiseX,
      currentY: noiseY,
    };
  };

  const setDotTransform = (dot, x, y) => {
    animeInstance.set(dot.element, {
      translateX: x,
      translateY: y,
      translateZ: 0,
    });
  };

  const draw = () => {
    frame = null;

    const settings = getSettings();
    const time = performance.now() * 0.001;
    const cursorEase = isActive ? 0.11 : 0.07;
    let needsFrame = isVisible;

    cursorX = lerp(cursorX, targetX, cursorEase);
    cursorY = lerp(cursorY, targetY, cursorEase);

    dots.forEach((dot) => {
      const distance = Math.hypot(dot.x - cursorX, dot.y - cursorY);
      const rawInfluence = isActive
        ? 1 -
          (distance - settings.innerRadius) /
            Math.max(1, settings.radius - settings.innerRadius)
        : 0;
      const influence =
        isActive && distance <= settings.innerRadius ? 1 : smooth(rawInfluence);
      const disorder = 1 - influence;
      const wiggleX =
        Math.cos(time * dot.wiggleSpeed + dot.wigglePhase) *
        settings.wiggle *
        dot.wiggleAmount *
        disorder;
      const wiggleY =
        Math.sin(time * (dot.wiggleSpeed * 0.73) + dot.wigglePhase * 1.37) *
        settings.wiggle *
        dot.wiggleAmount *
        disorder;
      const targetDotX = lerp(dot.noiseX, 0, influence);
      const targetDotY = lerp(dot.noiseY, 0, influence);

      dot.currentX = lerp(dot.currentX, targetDotX, 0.16);
      dot.currentY = lerp(dot.currentY, targetDotY, 0.16);

      if (
        Math.abs(dot.currentX - targetDotX) > 0.04 ||
        Math.abs(dot.currentY - targetDotY) > 0.04
      ) {
        needsFrame = true;
      }

      setDotTransform(dot, dot.currentX + wiggleX, dot.currentY + wiggleY);
    });

    if (needsFrame && isVisible && !reduceMotion.matches) {
      frame = requestAnimationFrame(draw);
    }
  };

  const requestDraw = () => {
    if (frame || reduceMotion.matches) return;

    frame = requestAnimationFrame(draw);
  };

  const buildDots = () => {
    const rect = hero.getBoundingClientRect();
    width = Math.max(1, rect.width);
    height = Math.max(1, rect.height);

    const settings = getSettings();
    const baseColumnCount = Math.max(1, Math.floor(width / settings.spacing));
    const baseRowCount = Math.max(1, Math.floor(height / settings.spacing));
    const totalSlots = baseColumnCount * baseRowCount;
    const densityScale = Math.max(1, Math.sqrt(totalSlots / settings.maxDots));
    const orderedSpacing = settings.spacing * densityScale;
    const columnCount = Math.max(1, Math.floor(width / orderedSpacing));
    const rowCount = Math.max(1, Math.floor(height / orderedSpacing));

    signal.replaceChildren();
    dots.length = 0;

    for (let row = 0; row < rowCount; row += 1) {
      for (let column = 0; column < columnCount; column += 1) {
        const x = ((column + 0.5) / columnCount) * width;
        const y = ((row + 0.5) / rowCount) * height;

        dots.push(createDot(x, y, settings.noise));
      }
    }

    targetX = width * 0.58;
    targetY = height * 0.48;
    cursorX = targetX;
    cursorY = targetY;

    animeInstance.set(
      dots.map((dot) => dot.element),
      { opacity: reduceMotion.matches ? 0.34 : 0 },
    );

    if (!reduceMotion.matches) {
      animeInstance({
        targets: dots.map((dot) => dot.element),
        opacity: [0, 0.5],
        delay: animeInstance.stagger(3, { from: "center" }),
        duration: 700,
        easing: "easeOutQuad",
      });
    }

    dots.forEach((dot) => {
      setDotTransform(dot, dot.noiseX, dot.noiseY);
    });

    requestDraw();
  };

  const queueBuild = () => {
    if (resizeFrame) return;

    resizeFrame = requestAnimationFrame(() => {
      resizeFrame = null;
      buildDots();
    });
  };

  const handlePointerMove = (event) => {
    if (reduceMotion.matches || !getSettings().interactive) return;

    const rect = hero.getBoundingClientRect();
    targetX = event.clientX - rect.left;
    targetY = event.clientY - rect.top;
    isActive = true;
    requestDraw();
  };

  const handlePointerLeave = () => {
    isActive = false;
    requestDraw();
  };

  const handleMotionChange = () => {
    if (frame) {
      cancelAnimationFrame(frame);
      frame = null;
    }

    buildDots();
  };

  const handleVisibilityChange = (entries) => {
    const entry = entries[0];
    isVisible = Boolean(entry && entry.isIntersecting);

    if (isVisible) {
      requestDraw();
    } else if (frame) {
      cancelAnimationFrame(frame);
      frame = null;
    }
  };

  buildDots();

  hero.addEventListener("pointermove", handlePointerMove, { passive: true });
  hero.addEventListener("pointerleave", handlePointerLeave);
  window.addEventListener("resize", queueBuild);
  window.addEventListener("mvillada:themechange", queueBuild);

  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver(handleVisibilityChange, {
      threshold: 0.05,
    });

    observer.observe(hero);
  }

  if (reduceMotion.addEventListener) {
    reduceMotion.addEventListener("change", handleMotionChange);
  } else if (reduceMotion.addListener) {
    reduceMotion.addListener(handleMotionChange);
  }
})();
