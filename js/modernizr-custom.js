/**
 * @license
 modernizr 3.3.1 (Custom Build) | MIT *
 https://modernizr.com/download/?-cssmask-setclasses !*/
'use strict';
!function(window, document, undefined) {
  /**
   * @param {string} obj
   * @param {string} type
   * @return {?}
   */
  function is(obj, type) {
    return typeof obj === type;
  }
  /**
   * @return {undefined}
   */
  function testRunner() {
    var ThetaGrad;
    var feature;
    var aliasIdx;
    var result;
    var i;
    var subwiki;
    var featureNameSplit;
    var featureIdx;
    for (featureIdx in tests) {
      if (tests.hasOwnProperty(featureIdx)) {
        if (ThetaGrad = [], feature = tests[featureIdx], feature.name && (ThetaGrad.push(feature.name.toLowerCase()), feature.options && feature.options.aliases && feature.options.aliases.length)) {
          /** @type {number} */
          aliasIdx = 0;
          for (; aliasIdx < feature.options.aliases.length; aliasIdx++) {
            ThetaGrad.push(feature.options.aliases[aliasIdx].toLowerCase());
          }
        }
        result = is(feature.fn, "function") ? feature.fn() : feature.fn;
        /** @type {number} */
        i = 0;
        for (; i < ThetaGrad.length; i++) {
          subwiki = ThetaGrad[i];
          featureNameSplit = subwiki.split(".");
          if (1 === featureNameSplit.length) {
            Modernizr[featureNameSplit[0]] = result;
          } else {
            if (!(!Modernizr[featureNameSplit[0]] || Modernizr[featureNameSplit[0]] instanceof Boolean)) {
              /** @type {!Boolean} */
              Modernizr[featureNameSplit[0]] = new Boolean(Modernizr[featureNameSplit[0]]);
            }
            Modernizr[featureNameSplit[0]][featureNameSplit[1]] = result;
          }
          classes.push((result ? "" : "no-") + featureNameSplit.join("-"));
        }
      }
    }
  }
  /**
   * @param {!Array} classes
   * @return {undefined}
   */
  function setClasses(classes) {
    var className = docElement.className;
    var classPrefix = Modernizr._config.classPrefix || "";
    if (isSVG && (className = className.baseVal), Modernizr._config.enableJSClass) {
      /** @type {!RegExp} */
      var reJS = new RegExp("(^|\\s)" + classPrefix + "no-js(\\s|$)");
      className = className.replace(reJS, "$1" + classPrefix + "js$2");
    }
    if (Modernizr._config.enableClasses) {
      className = className + (" " + classPrefix + classes.join(" " + classPrefix));
      if (isSVG) {
        docElement.className.baseVal = className;
      } else {
        docElement.className = className;
      }
    }
  }
  /**
   * @param {string} value
   * @param {string} key
   * @return {?}
   */
  function contains(value, key) {
    return !!~("" + value).indexOf(key);
  }
  /**
   * @return {?}
   */
  function createElement() {
    return "function" != typeof document.createElement ? document.createElement(arguments[0]) : isSVG ? document.createElementNS.call(document, "http://www.w3.org/2000/svg", arguments[0]) : document.createElement.apply(document, arguments);
  }
  /**
   * @param {string} name
   * @return {?}
   */
  function cssToDOM(name) {
    return name.replace(/([a-z])-([a-z])/g, function(canCreateDiscussions, n, shortMonthName) {
      return n + shortMonthName.toUpperCase();
    }).replace(/^-/, "");
  }
  /**
   * @param {!Function} e
   * @param {?} n
   * @return {?}
   */
  function resolve(e, n) {
    return function() {
      return e.apply(n, arguments);
    };
  }
  /**
   * @param {!Object} props
   * @param {string} obj
   * @param {string} elem
   * @return {?}
   */
  function testDOMProps(props, obj, elem) {
    var name;
    var i;
    for (i in props) {
      if (props[i] in obj) {
        return elem === false ? props[i] : (name = obj[props[i]], is(name, "function") ? resolve(name, elem || obj) : name);
      }
    }
    return false;
  }
  /**
   * @param {string} name
   * @return {?}
   */
  function domToCSS(name) {
    return name.replace(/([A-Z])/g, function(canCreateDiscussions, p_Interval) {
      return "-" + p_Interval.toLowerCase();
    }).replace(/^ms-/, "-ms-");
  }
  /**
   * @return {?}
   */
  function getBody() {
    /** @type {!HTMLBodyElement} */
    var body = document.body;
    return body || (body = createElement(isSVG ? "svg" : "body"), body.fake = true), body;
  }
  /**
   * @param {string} rule
   * @param {!Function} callback
   * @param {number} nodes
   * @param {string} testnames
   * @return {?}
   */
  function injectElementWithStyles(rule, callback, nodes, testnames) {
    var style;
    var ret;
    var node;
    var docOverflow;
    /** @type {string} */
    var mod = "modernizr";
    var div = createElement("div");
    var body = getBody();
    if (parseInt(nodes, 10)) {
      for (; nodes--;) {
        node = createElement("div");
        node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
        div.appendChild(node);
      }
    }
    return style = createElement("style"), style.type = "text/css", style.id = "s" + mod, (body.fake ? body : div).appendChild(style), body.appendChild(div), style.styleSheet ? style.styleSheet.cssText = rule : style.appendChild(document.createTextNode(rule)), div.id = mod, body.fake && (body.style.background = "", body.style.overflow = "hidden", docOverflow = docElement.style.overflow, docElement.style.overflow = "hidden", docElement.appendChild(body)), ret = callback(div, rule), body.fake ? (body.parentNode.removeChild(body), 
    docElement.style.overflow = docOverflow, docElement.offsetHeight) : div.parentNode.removeChild(div), !!ret;
  }
  /**
   * @param {!Object} props
   * @param {string} value
   * @return {?}
   */
  function nativeTestProps(props, value) {
    var i = props.length;
    if ("CSS" in window && "supports" in window.CSS) {
      for (; i--;) {
        if (window.CSS.supports(domToCSS(props[i]), value)) {
          return true;
        }
      }
      return false;
    }
    if ("CSSSupportsRule" in window) {
      /** @type {!Array} */
      var drilldownLevelLabels = [];
      for (; i--;) {
        drilldownLevelLabels.push("(" + domToCSS(props[i]) + ":" + value + ")");
      }
      return drilldownLevelLabels = drilldownLevelLabels.join(" or "), injectElementWithStyles("@supports (" + drilldownLevelLabels + ") { #modernizr { position: absolute; } }", function(anchor) {
        return "absolute" == getComputedStyle(anchor, null).position;
      });
    }
    return undefined;
  }
  /**
   * @param {!Object} props
   * @param {string} prefixed
   * @param {string} value
   * @param {boolean} skipValueTest
   * @return {?}
   */
  function testProps(props, prefixed, value, skipValueTest) {
    /**
     * @return {undefined}
     */
    function cleanElems() {
      if (p) {
        delete mStyle.style;
        delete mStyle.modElem;
      }
    }
    if (skipValueTest = is(skipValueTest, "undefined") ? false : skipValueTest, !is(value, "undefined")) {
      var result = nativeTestProps(props, value);
      if (!is(result, "undefined")) {
        return result;
      }
    }
    var p;
    var _l;
    var propsLength;
    var prop;
    var before;
    /** @type {!Array} */
    var elems = ["modernizr", "tspan", "samp"];
    for (; !mStyle.style && elems.length;) {
      /** @type {boolean} */
      p = true;
      mStyle.modElem = createElement(elems.shift());
      mStyle.style = mStyle.modElem.style;
    }
    propsLength = props.length;
    /** @type {number} */
    _l = 0;
    for (; propsLength > _l; _l++) {
      if (prop = props[_l], before = mStyle.style[prop], contains(prop, "-") && (prop = cssToDOM(prop)), mStyle.style[prop] !== undefined) {
        if (skipValueTest || is(value, "undefined")) {
          return cleanElems(), "pfx" == prefixed ? prop : true;
        }
        try {
          /** @type {string} */
          mStyle.style[prop] = value;
        } catch (g) {
        }
        if (mStyle.style[prop] != before) {
          return cleanElems(), "pfx" == prefixed ? prop : true;
        }
      }
    }
    return cleanElems(), false;
  }
  /**
   * @param {string} prop
   * @param {string} prefixed
   * @param {string} elem
   * @param {string} value
   * @param {!Object} skipValueTest
   * @return {?}
   */
  function testPropsAll(prop, prefixed, elem, value, skipValueTest) {
    var ucProp = prop.charAt(0).toUpperCase() + prop.slice(1);
    /** @type {!Array<string>} */
    var props = (prop + " " + cssomPrefixes.join(ucProp + " ") + ucProp).split(" ");
    return is(prefixed, "string") || is(prefixed, "undefined") ? testProps(props, prefixed, value, skipValueTest) : (props = (prop + " " + domPrefixes.join(ucProp + " ") + ucProp).split(" "), testDOMProps(props, prefixed, elem));
  }
  /**
   * @param {string} prop
   * @param {string} value
   * @param {string} skipValueTest
   * @return {?}
   */
  function testAllProps(prop, value, skipValueTest) {
    return testPropsAll(prop, undefined, undefined, value, skipValueTest);
  }
  /** @type {!Array} */
  var classes = [];
  /** @type {!Array} */
  var tests = [];
  var ModernizrProto = {
    _version : "3.3.1",
    _config : {
      classPrefix : "",
      enableClasses : true,
      enableJSClass : true,
      usePrefixes : true
    },
    _q : [],
    on : function(event, callback) {
      var processors = this;
      setTimeout(function() {
        callback(processors[event]);
      }, 0);
    },
    addTest : function(name, fn, options) {
      tests.push({
        name : name,
        fn : fn,
        options : options
      });
    },
    addAsyncTest : function(fn) {
      tests.push({
        name : null,
        fn : fn
      });
    }
  };
  /**
   * @return {undefined}
   */
  var Modernizr = function() {
  };
  Modernizr.prototype = ModernizrProto;
  Modernizr = new Modernizr;
  /** @type {!Element} */
  var docElement = document.documentElement;
  /** @type {boolean} */
  var isSVG = "svg" === docElement.nodeName.toLowerCase();
  /** @type {string} */
  var excludeLink = "Moz O ms Webkit";
  /** @type {!Array} */
  var cssomPrefixes = ModernizrProto._config.usePrefixes ? excludeLink.split(" ") : [];
  /** @type {!Array} */
  ModernizrProto._cssomPrefixes = cssomPrefixes;
  /** @type {!Array} */
  var domPrefixes = ModernizrProto._config.usePrefixes ? excludeLink.toLowerCase().split(" ") : [];
  /** @type {!Array} */
  ModernizrProto._domPrefixes = domPrefixes;
  var modElem = {
    elem : createElement("modernizr")
  };
  Modernizr._q.push(function() {
    delete modElem.elem;
  });
  var mStyle = {
    style : modElem.elem.style
  };
  Modernizr._q.unshift(function() {
    delete mStyle.style;
  });
  /** @type {function(string, string, string, string, !Object): ?} */
  ModernizrProto.testAllProps = testPropsAll;
  /** @type {function(string, string, string): ?} */
  ModernizrProto.testAllProps = testAllProps;
  Modernizr.addTest("cssmask", testAllProps("maskRepeat", "repeat-x", true));
  testRunner();
  setClasses(classes);
  delete ModernizrProto.addTest;
  delete ModernizrProto.addAsyncTest;
  /** @type {number} */
  var i = 0;
  for (; i < Modernizr._q.length; i++) {
    Modernizr._q[i]();
  }
  window.Modernizr = Modernizr;
}(window, document);
