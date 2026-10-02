const storySections = Array.from(document.querySelectorAll("[data-project-story-section]"));
const tocLinks = Array.from(document.querySelectorAll("[data-project-story-toc-link]"));
const progressBar = document.querySelector("[data-project-story-progress]");
const decisionSlider = document.querySelector("[data-project-story-slider]");
const decisionSlides = decisionSlider
  ? Array.from(decisionSlider.querySelectorAll("[data-project-story-slide]"))
  : [];
const previousDecisionButton = document.querySelector("[data-project-story-slider-prev]");
const nextDecisionButton = document.querySelector("[data-project-story-slider-next]");
let activeDecisionIndex = 0;

const setActiveSection = (id) => {
  tocLinks.forEach((link) => {
    link.classList.toggle("is-active", link.getAttribute("href") === `#${id}`);
  });
};

if (storySections.length && tocLinks.length && "IntersectionObserver" in window) {
  const observer = new IntersectionObserver((entries) => {
    const visible = entries
      .filter((entry) => entry.isIntersecting)
      .sort((first, second) => first.boundingClientRect.top - second.boundingClientRect.top);

    if (visible[0]) {
      setActiveSection(visible[0].target.id);
    }
  }, {
    rootMargin: "-20% 0px -65% 0px",
    threshold: 0,
  });

  storySections.forEach((section) => observer.observe(section));
}

const updateProgress = () => {
  if (!progressBar) return;

  const scrollTop = window.scrollY || document.documentElement.scrollTop;
  const max = document.documentElement.scrollHeight - window.innerHeight;
  const progress = max > 0 ? Math.min(scrollTop / max, 1) : 0;

  progressBar.style.width = `${Math.round(progress * 100)}%`;
};

window.addEventListener("scroll", updateProgress, { passive: true });
window.addEventListener("resize", updateProgress);
updateProgress();

const getActiveDecisionIndex = () => {
  return activeDecisionIndex;
};

const updateDecisionControls = () => {
  if (!previousDecisionButton || !nextDecisionButton || !decisionSlides.length) return;

  previousDecisionButton.disabled = activeDecisionIndex === 0;
  nextDecisionButton.disabled = activeDecisionIndex === decisionSlides.length - 1;

  decisionSlides.forEach((slide, index) => {
    slide.setAttribute("aria-hidden", index === activeDecisionIndex ? "false" : "true");
  });
};

const showDecision = (index) => {
  if (!decisionSlider || !decisionSlides.length) return;

  activeDecisionIndex = Math.max(0, Math.min(index, decisionSlides.length - 1));

  decisionSlider.style.setProperty("--project-story-slide-index", activeDecisionIndex);
  updateDecisionControls();
};

if (decisionSlider && decisionSlides.length) {
  if (previousDecisionButton) {
    previousDecisionButton.addEventListener("click", () => {
      showDecision(getActiveDecisionIndex() - 1);
    });
  }

  if (nextDecisionButton) {
    nextDecisionButton.addEventListener("click", () => {
      showDecision(getActiveDecisionIndex() + 1);
    });
  }

  decisionSlider.addEventListener("keydown", (event) => {
    if (event.key !== "ArrowLeft" && event.key !== "ArrowRight") return;

    event.preventDefault();
    showDecision(getActiveDecisionIndex() + (event.key === "ArrowRight" ? 1 : -1));
  });

  updateDecisionControls();
}
