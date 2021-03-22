"use strict";

/** All Selectors Should Be Here. */
const navMenuSelector = ".elementor-widget-nav-menu";

/** All Text Should Be Here. */
const navMenuMobileLabel = "Navigation Menu";

/**
 *
 * Wait Element Ready.
 *
 * @param {string} selector
 * @returns {array} Matches DOM
 */
const elementReady = (selector) => {
  return new Promise((resolve, reject) => {
    const el = document.querySelector(selector);
    if (el) {
      resolve(el);
    }
    new MutationObserver((mutationRecords, observer) => {
      const allElements = [];
      Array.from(document.querySelectorAll(selector)).forEach((element) => {
        allElements.push(element);
        resolve(allElements);
        observer.disconnect();
      });
    }).observe(document.documentElement, {
      childList: true,
      subtree: true,
    });
  });
};

/**
 * Nav Menu
 *
 * ISSUE
 * An element with aria-hidden=true contains focusable content.
 * The hidden element and its contents are not voiced by a screen reader,
 * but the contents contain an element with tabindex set or active a, button, input, select
 * and textarea controls. The user can tab to these or focus via touch and hears only silence.
 */
elementReady(navMenuSelector).then((navs) => {
  navs.forEach((nav) => {
    nav
      .querySelector("nav.elementor-nav-menu--dropdown")
      .setAttribute("aria-label", navMenuMobileLabel);
  });
});

/**
 * Anchor
 *
 * ISSUE
 * Each a element must contain text or an img with an alt attribute.
 * WCAG 2.0 A F89 Section 508 (2017) A F89	25 pages
 * A link name allows screen readers to voice what the links does.
 * If there is no link text or the `alt` text is blank,
 * screen readers have nothing to read, so read out the URL instead. To add a name do one of the following:
 *
 * Add text between the a element start and end tags
 * Add an aria-label attribute
 * Add an aria-labelledby attribute
 * Add an img alt attribute if the link contains an img element
 * img` elements with `display:none` or `visibility:hidden` are not included in the link's accessible name.
 * Some lazy loader scripts hide images until they're scrolled into view, but screen readers can access links below the fold.
 *
 * HOW IT WORKS:
 * is anchor have image? okei set aria-label by img alt
 * but img does not have alt! okei set aria-label to "decoration link"
 * if doesn't have img, set aria-label by innerHTML
 */
elementReady("a").then((anchors) => {
  anchors.forEach((anchor) => {
    if (!anchor.getAttribute("aria-label"))
      if (anchor.querySelector("img"))
        if (anchor.querySelector("img").getAttribute("alt"))
          anchor.setAttribute(
            "aria-label",
            anchor.querySelector("img").getAttribute("alt")
          );
        else anchor.setAttribute("aria-label", "decorations link");
      else if (anchor.querySelector("span"))
        anchor.querySelector("span").childElementCount ? anchor.setAttribute('aria-label', 'unamed link') : anchor.setAttribute('aria-label', anchor.querySelector("span").innerHTML);
      else anchor.childElementCount ? anchor.setAttribute("aria-label", "unamed link") : anchor.setAttribute("aria-label", anchor.innerHTML);
  });
});
