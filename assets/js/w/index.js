//index header
// 헤더 상태 변화 코드
const indexHeader = () => {
  const header = document.querySelector("header#header");
  const logo = header.querySelector(".logo");

  const headerTransparentize = () => {
    header.classList.replace("header__sticked", "header__transparent");
    logo.classList.replace("logo__filled", "logo__default");
  };

  const headerUntransparentize = () => {
    header.classList.replace("header__transparent", "header__sticked");
    logo.classList.replace("logo__default", "logo__filled");
  };

  // window.addEventListener("load", () => {
  //   headerTransparentize();
  //   header.addEventListener("mouseleave", () => {
  //     headerTransparentize();
  //   });
  // });

  let mouseBoolean = false;

  header.addEventListener("mouseenter", () => {
    mouseBoolean = true;
  });

  header.addEventListener("mouseleave", () => {
    mouseBoolean = false;
    header.classList.remove("header__sticked");
    logo.classList.remove("logo__filled");
    header.classList.add("header__transparent");
    logo.classList.add("logo__default");
  });

  header.addEventListener("mouseover", () => {
    // headerUntransparentize();
    header.classList.remove("header__transparent");
    logo.classList.remove("logo__default");
    header.classList.add("header__sticked");
    logo.classList.add("logo__filled");
  });

  window.addEventListener("scroll", () => {
    let scT = window.pageYOffset;
    const container = document.querySelector("#container");
    const abTcontainer = container.getBoundingClientRect().top;

    setTimeout(() => {
      if (scT < abTcontainer * 0.9 && mouseBoolean) {
        headerUntransparentize();
        header.addEventListener("mouseleave", () => {
          headerTransparentize();
        });
      } else if (scT < abTcontainer * 0.9 && !mouseBoolean) {
        headerTransparentize();
        header.addEventListener("mouseleave", () => {
          headerTransparentize();
        });
      } else {
        headerUntransparentize();
        header.addEventListener("mouseleave", () => {
          headerUntransparentize();
        });
      }
    }, 50);
  });
};

indexHeader();
// const indexHeader = () => {
//   const header = document.querySelector("header#header");
//   const logo = header.querySelector(".logo");

//   const headerTransparentize = () => {
//     header.classList.replace("header__sticked", "header__transparent");
//     logo.classList.replace("logo__filled", "logo__default");
//   };
//   const headerUntransparentize = () => {
//     header.classList.replace("header__transparent", "header__sticked");
//     logo.classList.replace("logo__default", "logo__filled");
//   };

//   window.addEventListener("load", () => {
//     headerTransparentize();
//     header.addEventListener("mouseleave", () => {
//       headerTransparentize();
//     });
//   });

//   let mouseBoolean = false;

//   header.addEventListener("mouseenter", () => {
//     mouseBoolean = true;
//   });
//   header.addEventListener("mouseleave", () => {
//     mouseBoolean = false;
//   });

//   header.addEventListener("mouseover", () => {
//     headerUntransparentize();
//   });

//   window.addEventListener("scroll", () => {
//     let scT = window.pageYOffset;
//     const container = document.querySelector("#container");
//     const abTcontainer = container.getBoundingClientRect().top;

//     setTimeout((delay) => {
//       if (scT < abTcontainer * 0.9 && mouseBoolean) {
//         headerUntransparentize();
//         header.addEventListener("mouseleave", () => {
//           headerTransparentize();
//         });
//       } else if (scT < abTcontainer * 0.9 && !mouseBoolean) {
//         headerTransparentize();
//         header.addEventListener("mouseleave", () => {
//           headerTransparentize();
//         });
//       } else {
//         headerUntransparentize();
//         header.addEventListener("mouseleave", () => {
//           headerUntransparentize();
//         });
//       }
//       clearTimeout(delay);
//     }, 50);
//   });
// };
// indexHeader();

//index kv
// window.addEventListener('load', () => {
//   const visual = document.querySelector('.visual');
//   const visualImage = document.querySelector('.visual__image .image');
//   const visualImages = document.querySelectorAll('.visual__image .image');

//   let i = 0;
//   let colorBg = ['#33dac6','#ffc809','#fb697e','#1682c2', '#42e17b', '#000000', '#9fe0a8', '#000000', '#631ba3', '#84bbec', '#ffa608'];

//   visualImages[0].style.opacity = 1;
//   visual.style.backgroundColor = colorBg[0];

//   const setKv = () => {
//     i++;
//     visualImages.forEach(() => {
//       if (i === visualImages.length) {
//         visualImages.forEach((item) => {item.style.opacity = 0;})
//         visual.style.backgroundColor = colorBg[0];
//         visualImages[0].style.opacity = 1;
//         i = 0;
//       } else {
//         if(i === 0) {visualImages.forEach((item) => {item.style.opacity = 0;})} else {visualImages[i-1].style.opacity = 0;}
//         visual.style.backgroundColor = colorBg[i];
//         visualImages[i].style.opacity = 1;
//       }
//     })
//   };

//   const stopKv = () => {
//     clearInterval(timer);
//   }
//   const timer = setInterval(setKv, 1750);
//   // setTimeout(stopKv, 10000);
// })

//setting min-height to each section needed
minHeights = () => {
  let wdH = window.innerHeight;
  const main = document.querySelector("body.main");
  const kv = main.querySelector(".visual");
  const area1 = main.querySelector('[data-section="service"]');
  const area1Cont = area1.querySelector(".section__content");
  let innerH = parseFloat(window.getComputedStyle(area1Cont).height);
  const area2 = main.querySelector('[data-section="performance"]');
  const area2Inner = area2.querySelectorAll(".box-item .box__title");

  setTimeout((delay) => {
    kv.style.height = wdH + "px";
    area1.style.height = innerH * 0.5 + "px";

    for (i = 0; i < area2Inner.length; i++) {
      area2Inner[i].style.minHeight = wdH + "px";
    }

    window.addEventListener("resize", () => {
      wdH = window.innerHeight;

      kv.style.height = wdH + "px";
      area1.style.height = innerH * 0.5 + "px";

      for (i = 0; i < area2Inner.length; i++) {
        area2Inner[i].style.minHeight = wdH + "px";
      }
    });

    clearTimeout(delay);
  }, 50);
};
minHeights();

//`service` section
anime1 = () => {
  let scT;
  let wdH = window.innerHeight;
  let wdW = document.body.clientWidth;
  const main = document.querySelector("body.main");
  const area = main.querySelector('[data-section="service"]');
  const areaInner = area.querySelector(".section__inner");
  const areaHead = area.querySelector(".section__head");
  const areaContImgs = area.querySelectorAll(".section__content .image-block");
  let innerW = parseFloat(window.getComputedStyle(areaInner).width);
  let HeadH = parseFloat(window.getComputedStyle(areaHead).height);
  let elemT = 150;
  let wdP = (wdW - innerW) * 0.5;

  let areaReach = area.getBoundingClientRect(true).top;
  let areaEndReach = area.getBoundingClientRect(true).bottom;

  areaInner.style.left = wdP + "px";

  window.addEventListener("resize", () => {
    wdH = window.innerHeight;
    wdW = document.body.clientWidth;
    innerW = parseFloat(window.getComputedStyle(areaInner).width);
    wdP = (wdW - innerW) * 0.5;
    areaInner.style.left = wdP + "px";
    areaHead.style.left = wdP + "px";
    areaReach = area.getBoundingClientRect(true).top;
    areaEndReach = area.getBoundingClientRect(true).bottom;
  });

  window.addEventListener("scroll", () => {
    scT = window.pageYOffset;
    areaReach = area.getBoundingClientRect(true).top;
    areaEndReach = area.getBoundingClientRect(true).bottom;

    for (i = 0; i < areaContImgs.length; i++) {
      if (areaContImgs[i].getBoundingClientRect().top <= wdH + elemT) {
        areaContImgs[i].classList.add("slidein");
      }
    }

    setTimeout((delay) => {
      if (areaReach <= elemT) {
        area.classList.add("approached");
        areaHead.style.cssText = `top: ${elemT}px; left: ${wdP}px; bottom: auto;`;
      } else {
        area.classList.remove("approached");
        areaHead.style.cssText = `top: 0; left: 0; bottom: auto;`;
      }
      if (areaEndReach <= elemT * 2 + HeadH + 50) {
        area.classList.remove("approached");
        areaHead.style.cssText = `top: auto; left: 0; bottom: ${elemT + 50}px;`;
      }
      clearTimeout(delay);
    }, 50);
  });
};
anime1();

//`partners` section
anime2 = () => {
  let scT;
  let wdH = window.innerHeight;
  const main = document.querySelector("body.main");
  const graph = main.querySelector('[data-section="partners"]');
  let graphAbT;

  window.addEventListener("resize", () => {
    wdH = window.innerHeight;
  });

  window.addEventListener("scroll", () => {
    scT = window.pageYOffset;
    graphAbT = scT + graph.getBoundingClientRect().top;

    setTimeout((delay) => {
      scT > graphAbT - wdH
        ? graph.classList.add("approached")
        : graph.classList.remove("approached");
      clearTimeout(delay);
    }, 50);
  });
};
anime2();

//accordion UI in `performance` section
const serviceArea = document.querySelector(
  'section[data-section="performance"]'
);
const listItems = serviceArea.querySelectorAll(".accordian__item");
const boxItems = serviceArea.querySelectorAll(
  ".box-item--left, .box-item--right"
);

listItems.forEach((item) => {
  item.addEventListener("mouseenter", () => {
    listItems.forEach((targets) => {
      targets.classList.remove("accordian__item--active");
    });
    let result = item.classList.contains("accordian__item--active");
    !result
      ? item.classList.add("accordian__item--active")
      : item.classList.remove("accordian__item--active");
  });
  item.addEventListener("mouseleave", () => {
    listItems.forEach((targets) => {
      targets.classList.remove("accordian__item--active");
    });
  });
});

//a11y tab focus
const targetArrowBtn = serviceArea.querySelector(".main .box-item--left ");

boxItems.forEach((item) => {
  item.addEventListener("mouseover", () => {
    // item.classList.add("item--is-visible");
  });
  item.addEventListener("mouseleave", () => {
    // item.classList.remove("item--is-visible");
  });
  item.querySelector(".box__title").addEventListener("focus", () => {
    // item.classList.add("item--is-visible");
  });
  item.querySelector(".box__title").addEventListener("!focus", () => {
    // item.classList.remove("item--is-visible");
  });
});

//main-popup

const startDate = new Date("2026/03/10 23:59:59");
const endDate = new Date("2026/03/27 10:00:00");
const today = Date.now();

if (today >= startDate && today <= endDate) {
  const setCookie = function (name, value, exp) {
    var date = new Date();
    date.setTime(date.getTime() + exp * 24 * 60 * 60 * 1000);
    document.cookie =
      name + "=" + value + ";expires=" + date.toUTCString() + ";path=/";
  };

  const getCookie = function (name) {
    var value = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
    return value ? value[2] : null;
  };

  if (!getCookie("main-popup--checked")) {
    layerOpen("Notice");
  }

  document
    .querySelector(".main-popup .layer__close .close")
    .addEventListener("click", () => {
      if (document.querySelector(".main-popup #checkbox--popup:checked")) {
        setCookie("main-popup--checked", "true", 1);
        layerClose("Notice");
      } else {
        layerClose("Notice");
      }
    });

  document
    .querySelector(".main-popup .button__wrap .close")
    .addEventListener("click", () => {
      if (document.querySelector(".main-popup #checkbox--popup:checked")) {
        setCookie("main-popup--checked", "true", 1);
        layerClose("Notice");
      } else {
        layerClose("Notice");
      }
    });
}

$(document).ready(function () {
  const $boxVideo = $(".box-item--right .box__video video");
  const $boxVideo2 = $(".box-item--left .box__video video");

  function playVideo() {
    $(".box-item--right .box__video").css("opacity", "1");
    $boxVideo.get(0).play();
  }
  function pauseVideo() {
    $(".box-item--right .box__video").css("opacity", "0");
    $boxVideo.get(0).pause();
    $boxVideo.get(0).currentTime = 0;
  }

  function playVideo2() {
    $(".box-item--left .box__video").css("opacity", "1");
    $boxVideo2.get(0).play();
  }
  function pauseVideo2() {
    $(".box-item--left .box__video").css("opacity", "0");
    $boxVideo2.get(0).pause();
    $boxVideo2.get(0).currentTime = 0;
  }

  $(".box-item--right .box__title").on("mouseover", playVideo);
  $(".box-item--right").on("mouseleave", pauseVideo);

  $(".box-item--left .box__title").on("mouseover", playVideo2);
  $(".box-item--left").on("mouseleave", pauseVideo2);
});
