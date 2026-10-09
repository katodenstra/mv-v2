const root = document.documentElement;
const body = document.body;
const themeToggle = document.querySelector(".theme-toggle");
const storageKey = "mvillada-theme";

const getSavedTheme = () => {
  try {
    return localStorage.getItem(storageKey) || "dark";
  } catch (error) {
    return "dark";
  }
};

const saveTheme = (theme) => {
  try {
    localStorage.setItem(storageKey, theme);
  } catch (error) {
    return;
  }
};

const applyTheme = (theme) => {
  root.setAttribute("data-theme", theme);
  body.setAttribute("data-theme", theme);
  window.dispatchEvent(new Event("mvillada:themechange"));

  if (!themeToggle) return;

  const icon = themeToggle.querySelector(".material-symbols-rounded");

  if (icon) {
    icon.textContent = theme === "dark" ? "light_mode" : "dark_mode";
  }

  themeToggle.setAttribute(
    "aria-label",
    theme === "dark" ? "Switch to light theme" : "Switch to dark theme",
  );
};

const savedTheme = getSavedTheme();
applyTheme(savedTheme);

if (themeToggle) {
  themeToggle.addEventListener("click", () => {
    const currentTheme = root.getAttribute("data-theme") || "dark";
    const nextTheme = currentTheme === "dark" ? "light" : "dark";

    applyTheme(nextTheme);
    saveTheme(nextTheme);
  });
}

const initSiteNavigation = () => {
  const header = document.querySelector("[data-site-header]");
  const nav = document.querySelector("[data-site-nav]");
  const navToggle = document.querySelector("[data-site-nav-toggle]");
  const dropdowns = Array.from(document.querySelectorAll("[data-nav-dropdown]"));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  let ignoreNextFocusOpenUntil = 0;

  const setHeaderHeight = () => {
    if (!header) return;

    const headerInner = header.querySelector(".site-header__inner");
    const headerHeight = headerInner ? headerInner.offsetHeight : header.offsetHeight;

    root.style.setProperty("--site-header-height", `${headerHeight}px`);
  };

  const closeDropdown = (dropdown) => {
    const button = dropdown.querySelector("[data-nav-dropdown-toggle]");

    dropdown.classList.remove("is-open");

    if (button) {
      button.setAttribute("aria-expanded", "false");
    }
  };

  const closeDropdowns = (except = null) => {
    dropdowns.forEach((dropdown) => {
      if (dropdown !== except) closeDropdown(dropdown);
    });
  };

  const openDropdown = (dropdown) => {
    const button = dropdown.querySelector("[data-nav-dropdown-toggle]");

    closeDropdowns(dropdown);
    dropdown.classList.add("is-open");

    if (button) {
      button.setAttribute("aria-expanded", "true");
    }
  };

  const toggleDropdown = (dropdown) => {
    if (dropdown.classList.contains("is-open")) {
      closeDropdown(dropdown);
      return;
    }

    openDropdown(dropdown);
  };

  const closeMobileNav = () => {
    if (!nav || !navToggle) return;

    const navToggleIcon = navToggle.querySelector(".material-symbols-rounded");

    nav.classList.remove("is-open");
    navToggle.setAttribute("aria-expanded", "false");

    if (navToggleIcon) {
      navToggleIcon.textContent = "menu";
    }
  };

  const scrollToHash = (hash, updateUrl = true) => {
    const target = document.querySelector(hash);

    if (!target) return false;

    const headerHeight = header ? header.offsetHeight : 0;
    const adminBar = document.getElementById("wpadminbar");
    const adminBarHeight = adminBar ? adminBar.offsetHeight : 0;
    const top =
      window.scrollY +
      target.getBoundingClientRect().top -
      headerHeight -
      adminBarHeight -
      24;

    window.scrollTo({
      top: Math.max(0, top),
      behavior: reduceMotion.matches ? "auto" : "smooth",
    });

    if (updateUrl && window.history.pushState) {
      window.history.pushState(null, "", hash);
    }

    return true;
  };

  setHeaderHeight();

  if ("ResizeObserver" in window && header) {
    const headerInner = header.querySelector(".site-header__inner");

    new ResizeObserver(setHeaderHeight).observe(headerInner || header);
  } else {
    window.addEventListener("resize", setHeaderHeight);
  }

  if (navToggle && nav) {
    navToggle.addEventListener("click", () => {
      const navToggleIcon = navToggle.querySelector(".material-symbols-rounded");
      const isOpen = nav.classList.toggle("is-open");

      navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");

      if (navToggleIcon) {
        navToggleIcon.textContent = isOpen ? "close" : "menu";
      }

      if (!isOpen) closeDropdowns();
    });
  }

  dropdowns.forEach((dropdown) => {
    const button = dropdown.querySelector("[data-nav-dropdown-toggle]");

    if (!button) return;

    dropdown.addEventListener("pointerenter", (event) => {
      if (event.pointerType === "mouse") openDropdown(dropdown);
    });

    dropdown.addEventListener("pointerleave", (event) => {
      if (event.pointerType === "mouse") closeDropdown(dropdown);
    });

    dropdown.addEventListener("focusin", () => {
      if (Date.now() < ignoreNextFocusOpenUntil) return;

      openDropdown(dropdown);
    });

    button.addEventListener("click", () => {
      toggleDropdown(dropdown);
    });

    dropdown.addEventListener("focusout", () => {
      window.setTimeout(() => {
        if (!dropdown.contains(document.activeElement)) {
          closeDropdown(dropdown);
        }
      }, 0);
    });
  });

  document.addEventListener("pointerdown", () => {
    ignoreNextFocusOpenUntil = Date.now() + 250;
  });

  document.addEventListener("click", (event) => {
    const clickedInsideNav = nav && nav.contains(event.target);
    const clickedToggle = navToggle && navToggle.contains(event.target);

    if (!clickedInsideNav && !clickedToggle) {
      closeDropdowns();
      closeMobileNav();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;

    closeDropdowns();
    closeMobileNav();
  });

  document.querySelectorAll('a[href*="#"]').forEach((link) => {
    link.addEventListener("click", (event) => {
      const url = new URL(link.href, window.location.href);

      if (
        url.origin !== window.location.origin ||
        url.pathname !== window.location.pathname ||
        !url.hash
      ) {
        return;
      }

      if (scrollToHash(url.hash)) {
        event.preventDefault();
        closeDropdowns();
        closeMobileNav();
      }
    });
  });

  if (window.location.hash) {
    window.setTimeout(() => {
      scrollToHash(window.location.hash, false);
    }, 80);
  }
};

const initHeroWave = () => {
  const canvas = document.querySelector("[data-hero-wave]");

  if (!canvas) return;

  const context = canvas.getContext("2d", { alpha: true });

  if (!context) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const lineCount = 21;
  const dprLimit = 2;
  const flatPhaseDuration = 1200;
  const shapeInPhaseDuration = 1200;
  const spreadPhaseDuration = 1200;
  const orderedPhaseDuration = 5200;
  const collapsePhaseDuration = 2200;
  const flattenPhaseDuration = 1600;
  const totalCycleDuration =
    flatPhaseDuration +
    shapeInPhaseDuration +
    spreadPhaseDuration +
    orderedPhaseDuration +
    collapsePhaseDuration +
    flattenPhaseDuration;
  const crestSafetyScale = 0.82;
  const orderedMotionSpeed = 0.018;
  const colorFallbacks = {
    "--wave-blue-light": "#ff0000",
    "--wave-blue-dark": "#15244c",
    "--wave-blue-mid": "#3f6fb8",
    "--wave-turquoise": "#50bfbf",
    "--wave-turquoise-light": "#a1d1cc",
  };

  let width = 0;
  let height = 0;
  let pixelRatio = 1;
  let animationFrame = null;
  let resizeFrame = null;
  let isVisible = true;
  let isReduced = reduceMotion.matches;

  const getColor = (name) => {
    const value = getComputedStyle(root).getPropertyValue(name).trim();

    return value || colorFallbacks[name];
  };

  const createGradient = () => {
    const gradient = context.createLinearGradient(0, 0, width, 0);

    gradient.addColorStop(0, getColor("--wave-blue-light"));
    gradient.addColorStop(0.18, getColor("--wave-blue-dark"));
    gradient.addColorStop(0.34, getColor("--wave-blue-mid"));
    gradient.addColorStop(0.55, getColor("--wave-turquoise"));
    gradient.addColorStop(0.78, getColor("--wave-turquoise-light"));
    gradient.addColorStop(0.89, getColor("--wave-blue-light"));
    gradient.addColorStop(1, getColor("--wave-blue-dark"));

    return gradient;
  };

  const setCanvasSize = () => {
    const rect = canvas.getBoundingClientRect();
    width = Math.max(1, rect.width);
    height = Math.max(1, rect.height);
    pixelRatio = Math.min(window.devicePixelRatio || 1, dprLimit);

    canvas.width = Math.round(width * pixelRatio);
    canvas.height = Math.round(height * pixelRatio);
    context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
  };

  const lerp = (start, end, amount) => start + (end - start) * amount;
  const clamp01 = (value) => Math.max(0, Math.min(1, value));
  const ease = (value) => {
    const safeValue = clamp01(value);

    return safeValue * safeValue * (3 - 2 * safeValue);
  };

  const orderedWaveY = (x, phase, amplitudeMultiplier = 1) => {
    const baseAmplitude =
      Math.min(height * 0.65, width * 0.32) * crestSafetyScale;
    const amplitude = baseAmplitude * amplitudeMultiplier;
    const wavelength = width * 0.65;
    const angle = ((x + phase) / wavelength + 0.08) * Math.PI * 2;

    return Math.sin(angle) * amplitude;
  };

  const drawSmoothPath = (points) => {
    if (points.length < 2) return;

    context.beginPath();
    context.moveTo(points[0].x, points[0].y);

    for (let index = 1; index < points.length - 1; index += 1) {
      const current = points[index];
      const next = points[index + 1];
      const midpointX = (current.x + next.x) / 2;
      const midpointY = (current.y + next.y) / 2;

      context.quadraticCurveTo(current.x, current.y, midpointX, midpointY);
    }

    const last = points[points.length - 1];
    context.lineTo(last.x, last.y);
  };

  const draw = (timestamp = 0) => {
    context.clearRect(0, 0, width, height);
    context.lineCap = "round";
    context.lineJoin = "round";
    context.strokeStyle = createGradient();

    const centerOffset = height * 0.5;
    const orderedMotionPhase = isReduced
      ? width * 0.08
      : timestamp * orderedMotionSpeed;
    const baseContourPhaseStep = (width * 0.2) / (lineCount - 1);
    const bleed = Math.max(160, width * 0.28);
    const step = Math.max(3, width / 260);
    const xSamples = [];

    for (let x = -bleed; x <= width + bleed; x += step) {
      xSamples.push(x);
    }

    const cycleTime = isReduced
      ? totalCycleDuration
      : timestamp % totalCycleDuration;

    const flatPhaseEnd = flatPhaseDuration;
    const shapeInPhaseEnd = flatPhaseEnd + shapeInPhaseDuration;
    const spreadPhaseEnd = shapeInPhaseEnd + spreadPhaseDuration;
    const orderedPhaseEnd = spreadPhaseEnd + orderedPhaseDuration;
    const collapsePhaseEnd = orderedPhaseEnd + collapsePhaseDuration;
    const flattenPhaseEnd = collapsePhaseEnd + flattenPhaseDuration;

    let shapeStrength = 0;
    let spreadStrength = 0;

    if (cycleTime < flatPhaseEnd) {
      shapeStrength = 0;
      spreadStrength = 0;
    } else if (cycleTime < shapeInPhaseEnd) {
      const localProgress = (cycleTime - flatPhaseEnd) / shapeInPhaseDuration;
      shapeStrength = ease(localProgress);
      spreadStrength = 0;
    } else if (cycleTime < spreadPhaseEnd) {
      const localProgress = (cycleTime - shapeInPhaseEnd) / spreadPhaseDuration;
      shapeStrength = 1;
      spreadStrength = ease(localProgress);
    } else if (cycleTime < orderedPhaseEnd) {
      shapeStrength = 1;
      spreadStrength = 1;
    } else if (cycleTime < collapsePhaseEnd) {
      const localProgress =
        (cycleTime - orderedPhaseEnd) / collapsePhaseDuration;
      shapeStrength = 1;
      spreadStrength = 1 - ease(localProgress);
    } else if (cycleTime < flattenPhaseEnd) {
      const localProgress =
        (cycleTime - collapsePhaseEnd) / flattenPhaseDuration;
      shapeStrength = 1 - ease(localProgress);
      spreadStrength = 0;
    } else {
      shapeStrength = 0;
      spreadStrength = 0;
    }

    const contourPhaseStep = baseContourPhaseStep;
    const maxStartPhase = ((lineCount - 1) * contourPhaseStep) / -2;
    const effectiveStartPhase = maxStartPhase * spreadStrength;
    const effectiveContourPhaseStep = contourPhaseStep * spreadStrength;

    for (let index = 0; index < lineCount; index += 1) {
      const contourPhase =
        effectiveStartPhase + index * effectiveContourPhaseStep;
      const distanceFromCenter =
        Math.abs(index - (lineCount - 1) / 2) / ((lineCount - 1) / 2);
      const baseOpacity = 0.5 + (1 - distanceFromCenter) * 0.18;
      const collapsedOpacityBoost = 0.18 * (1 - spreadStrength);
      const points = [];

      context.globalAlpha = Math.min(0.95, baseOpacity + collapsedOpacityBoost);
      context.lineWidth = 1;

      for (
        let sampleIndex = 0;
        sampleIndex < xSamples.length;
        sampleIndex += 1
      ) {
        const x = xSamples[sampleIndex];

        points.push({
          x,
          y:
            centerOffset +
            orderedWaveY(x, orderedMotionPhase + contourPhase, 1) *
              shapeStrength,
        });
      }

      drawSmoothPath(points);
      context.stroke();
    }

    context.globalAlpha = 1;
  };

  const animate = (timestamp) => {
    if (!isVisible || isReduced) {
      animationFrame = null;
      return;
    }

    draw(timestamp);
    animationFrame = requestAnimationFrame(animate);
  };

  const start = () => {
    if (animationFrame || isReduced || !isVisible) return;

    animationFrame = requestAnimationFrame(animate);
  };

  const stop = () => {
    if (!animationFrame) return;

    cancelAnimationFrame(animationFrame);
    animationFrame = null;
  };

  const render = () => {
    setCanvasSize();
    draw();
    start();
  };

  const queueResize = () => {
    if (resizeFrame) return;

    resizeFrame = requestAnimationFrame(() => {
      resizeFrame = null;
      render();
    });
  };

  const visibilityObserver =
    "IntersectionObserver" in window
      ? new IntersectionObserver(
          (entries) => {
            isVisible = entries[0] ? entries[0].isIntersecting : true;

            if (isVisible) {
              draw();
              start();
            } else {
              stop();
            }
          },
          { threshold: 0.12 },
        )
      : null;

  const resizeObserver =
    "ResizeObserver" in window ? new ResizeObserver(queueResize) : null;

  const handleMotionPreferenceChange = (event) => {
    isReduced = event.matches;
    stop();
    draw();
    start();
  };

  if (reduceMotion.addEventListener) {
    reduceMotion.addEventListener("change", handleMotionPreferenceChange);
  } else if (reduceMotion.addListener) {
    reduceMotion.addListener(handleMotionPreferenceChange);
  }

  window.addEventListener("mvillada:themechange", draw);
  window.addEventListener("resize", queueResize);

  if (resizeObserver) {
    resizeObserver.observe(canvas);
  }

  if (visibilityObserver) {
    visibilityObserver.observe(canvas);
  }

  render();
};

const initTestimonialSliders = () => {
  const sliders = document.querySelectorAll("[data-testimonial-slider]");

  sliders.forEach((slider) => {
    const slides = Array.from(
      slider.querySelectorAll("[data-testimonial-slide]"),
    );
    const dots = Array.from(slider.querySelectorAll("[data-testimonial-dot]"));
    const previousButton = slider.querySelector("[data-testimonial-prev]");
    const nextButton = slider.querySelector("[data-testimonial-next]");

    if (!slides.length) return;

    let activeIndex = slides.findIndex((slide) =>
      slide.classList.contains("is-active"),
    );

    if (activeIndex < 0) {
      activeIndex = 0;
    }

    const setSliderHeight = () => {
      slider.style.removeProperty("--testimonial-slider-height");

      const tallestSlide = slides.reduce((height, slide) => {
        const previousStyles = {
          inset: slide.style.inset,
          minHeight: slide.style.minHeight,
          opacity: slide.style.opacity,
          position: slide.style.position,
          visibility: slide.style.visibility,
        };

        slide.style.inset = "auto";
        slide.style.minHeight = "0";
        slide.style.opacity = "0";
        slide.style.position = "relative";
        slide.style.visibility = "hidden";

        const slideHeight = slide.getBoundingClientRect().height;

        slide.style.inset = previousStyles.inset;
        slide.style.minHeight = previousStyles.minHeight;
        slide.style.opacity = previousStyles.opacity;
        slide.style.position = previousStyles.position;
        slide.style.visibility = previousStyles.visibility;

        return Math.max(height, slideHeight);
      }, 0);

      if (tallestSlide > 0) {
        slider.style.setProperty(
          "--testimonial-slider-height",
          `${Math.ceil(tallestSlide)}px`,
        );
      }
    };

    const showSlide = (nextIndex) => {
      activeIndex = (nextIndex + slides.length) % slides.length;

      slides.forEach((slide, index) => {
        const isActive = index === activeIndex;
        slide.classList.toggle("is-active", isActive);
        slide.setAttribute("aria-hidden", isActive ? "false" : "true");
      });

      dots.forEach((dot, index) => {
        const isActive = index === activeIndex;
        dot.classList.toggle("is-active", isActive);
        dot.setAttribute("aria-current", isActive ? "true" : "false");
      });
    };

    if (previousButton) {
      previousButton.addEventListener("click", () => {
        showSlide(activeIndex - 1);
      });
    }

    if (nextButton) {
      nextButton.addEventListener("click", () => {
        showSlide(activeIndex + 1);
      });
    }

    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        showSlide(index);
      });
    });

    setSliderHeight();
    window.addEventListener("resize", setSliderHeight);

    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(setSliderHeight);
    }

    showSlide(activeIndex);
  });
};

const initCaseStudyDecisionCards = () => {
  const contentBlocks = document.querySelectorAll(".case-study-content");

  contentBlocks.forEach((content) => {
    const headings = Array.from(content.querySelectorAll("h2, h3"));
    const normalizeHeading = (heading) =>
      heading.textContent.trim().toLowerCase().replace(/:$/, "");
    const decisionHeading = headings.find(
      (heading) => normalizeHeading(heading) === "key decisions",
    );

    if (!decisionHeading) return;

    decisionHeading.classList.add("case-study-decisions-heading");

    const sectionNodes = [];
    let sibling = decisionHeading.nextElementSibling;

    while (sibling && !/^H2$/i.test(sibling.tagName)) {
      sectionNodes.push(sibling);

      if (/^(UL|OL)$/i.test(sibling.tagName)) {
        sibling.classList.add("case-study-decision-list");

        Array.from(sibling.children).forEach((item) => {
          item.classList.add("case-study-decision-card", "has-card-glow");
        });
      }

      sibling = sibling.nextElementSibling;
    }

    if (sectionNodes.some((node) => /^(UL|OL)$/i.test(node.tagName))) return;

    const decisionSubheadings = sectionNodes.filter((node) =>
      /^H3$/i.test(node.tagName),
    );

    if (!decisionSubheadings.length) return;

    const cardGrid = document.createElement("div");
    cardGrid.className = "case-study-decision-list";
    decisionHeading.insertAdjacentElement("afterend", cardGrid);

    let currentCard = null;

    sectionNodes.forEach((node) => {
      if (/^H3$/i.test(node.tagName)) {
        currentCard = document.createElement("article");
        currentCard.className = "case-study-decision-card has-card-glow";
        cardGrid.append(currentCard);
      }

      if (currentCard) {
        currentCard.append(node);
      }
    });
  });
};

const initCardGlows = () => {
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const cards = Array.from(document.querySelectorAll(".has-card-glow")).filter(
    (card) => !card.closest(".perspective-section"),
  );
  const stopAnimations = [];

  cards.forEach((card) => {
    let frame = null;
    let currentX = 50;
    let currentY = 50;
    let targetX = 50;
    let targetY = 50;
    const followEase = 0.035;

    const setGlowPosition = (x, y) => {
      card.style.setProperty("--glow-x", `${x.toFixed(2)}%`);
      card.style.setProperty("--glow-y", `${y.toFixed(2)}%`);
    };

    const updateTarget = (event) => {
      const rect = card.getBoundingClientRect();

      targetX = ((event.clientX - rect.left) / rect.width) * 100;
      targetY = ((event.clientY - rect.top) / rect.height) * 100;
    };

    const stop = () => {
      if (!frame) return;

      cancelAnimationFrame(frame);
      frame = null;
    };

    stopAnimations.push(stop);

    const animate = () => {
      currentX += (targetX - currentX) * followEase;
      currentY += (targetY - currentY) * followEase;

      setGlowPosition(currentX, currentY);

      if (
        Math.abs(targetX - currentX) > 0.05 ||
        Math.abs(targetY - currentY) > 0.05
      ) {
        frame = requestAnimationFrame(animate);
        return;
      }

      frame = null;
    };

    const start = () => {
      if (reduceMotion.matches || frame) return;

      frame = requestAnimationFrame(animate);
    };

    card.addEventListener("pointerenter", (event) => {
      if (event.pointerType !== "mouse") return;

      updateTarget(event);
      start();
    });

    card.addEventListener("pointermove", (event) => {
      if (event.pointerType !== "mouse" || reduceMotion.matches) return;

      updateTarget(event);
      start();
    });

    card.addEventListener("pointerleave", stop);
    card.addEventListener("blur", stop, true);
  });

  const handleMotionPreferenceChange = () => {
    if (!reduceMotion.matches) return;

    stopAnimations.forEach((stop) => stop());
  };

  if (reduceMotion.addEventListener) {
    reduceMotion.addEventListener("change", handleMotionPreferenceChange);
  } else if (reduceMotion.addListener) {
    reduceMotion.addListener(handleMotionPreferenceChange);
  }
};

const initCaseStudyRoleHighlight = () => {
  const contentBlocks = document.querySelectorAll(".case-study-content");

  contentBlocks.forEach((content) => {
    const headings = Array.from(content.querySelectorAll("h2, h3"));
    const normalizeHeading = (heading) =>
      heading.textContent.trim().toLowerCase().replace(/:$/, "");
    const roleHeading = headings.find(
      (heading) => normalizeHeading(heading) === "my role",
    );

    if (!roleHeading) return;

    const section = document.createElement("section");
    const inner = document.createElement("div");
    let sibling = roleHeading.nextElementSibling;

    section.className = "case-study-role-highlight";
    inner.className = "case-study-role-highlight__inner";

    roleHeading.insertAdjacentElement("beforebegin", section);
    section.append(inner);
    inner.append(roleHeading);

    while (sibling && !/^H2$/i.test(sibling.tagName)) {
      const nextSibling = sibling.nextElementSibling;
      inner.append(sibling);
      sibling = nextSibling;
    }
  });
};

const initPerspectiveStack = () => {
  const section = document.querySelector("[data-perspective-section]");
  const stack = document.querySelector("[data-perspective-stack]");
  const cards = Array.from(
    document.querySelectorAll("[data-perspective-card]"),
  );

  if (!section || !stack || !cards.length) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const staticLayout = window.matchMedia("(max-width: 860px)");
  let frame = null;
  let sectionTop = 0;
  let scrollRange = 1;

  const clamp01 = (value) => Math.max(0, Math.min(1, value));
  const ease = (value) => {
    const safeValue = clamp01(value);

    return safeValue * safeValue * (3 - 2 * safeValue);
  };

  const measure = () => {
    const rect = stack.getBoundingClientRect();
    sectionTop = window.scrollY + rect.top;
    scrollRange = Math.max(1, stack.offsetHeight - window.innerHeight);
  };

  const setStaticCards = () => {
    cards.forEach((card) => {
      card.style.opacity = "";
      card.style.transform = "";
    });
  };

  const update = () => {
    frame = null;

    if (reduceMotion.matches || staticLayout.matches) {
      setStaticCards();
      return;
    }

    const sectionProgress = clamp01(
      (window.scrollY - sectionTop) / scrollRange,
    );
    const transitionCount = Math.max(1, cards.length - 1);
    const stackProgress = sectionProgress * transitionCount + 1;

    cards.forEach((card, index) => {
      const cardProgress = ease(stackProgress - index);
      const entryY = (1 - cardProgress) * window.innerHeight * 0.92;
      const settledY = index * 18;
      const settledScale = 0.94 + index * 0.012;
      const scale = 0.985 + cardProgress * (settledScale - 0.985);
      const opacity = clamp01(cardProgress * 1.35);

      card.style.opacity = opacity.toFixed(3);
      card.style.transform = `translate3d(0, ${entryY + settledY}px, 0) scale(${scale.toFixed(3)})`;
    });
  };

  const requestUpdate = () => {
    if (frame) return;

    frame = window.requestAnimationFrame(update);
  };

  const watchMediaQuery = (query, callback) => {
    if (query.addEventListener) {
      query.addEventListener("change", callback);
      return;
    }

    if (query.addListener) {
      query.addListener(callback);
    }
  };

  measure();
  update();

  window.addEventListener("scroll", requestUpdate, { passive: true });
  window.addEventListener("resize", () => {
    measure();
    requestUpdate();
  });

  watchMediaQuery(reduceMotion, () => {
    measure();
    requestUpdate();
  });

  watchMediaQuery(staticLayout, () => {
    measure();
    requestUpdate();
  });
};

const initPage = () => {
  initSiteNavigation();
  initHeroWave();
  initTestimonialSliders();
  initCaseStudyDecisionCards();
  initCaseStudyRoleHighlight();
  initPerspectiveStack();
  initCardGlows();
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initPage);
} else {
  initPage();
}
