window.addEventListener("load", () => {
  //go-top-button
  window.addEventListener("scroll", () => {
    let sct = window.pageYOffset;

    if (sct > 700) {
      document.querySelector(".top-button").style.display = "block";
      document.querySelector(".top-button a").style.position = "fixed";
      document.querySelector(".top-button a").style.bottom = "25px";
    } else {
      document.querySelector(".top-button").style.display = "none";
    }
  });

  document.querySelector(".top-button").addEventListener("click", (event) => {
    event.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  //Header
  const header = document.querySelector("header#header");
  window.addEventListener("scroll", () => {
    let scT = window.pageYOffset;
    const container = document.querySelector("#container");
    const abTcontainer = container.getBoundingClientRect().top;
    setTimeout((delay) => {
      scT > abTcontainer * 0.9
        ? header.classList.add("header--scrolled")
        : header.classList.remove("header--scrolled");
      clearTimeout(delay);
    }, 50);
  });

  //HeaderNav
  const nav = document.querySelector("header#header nav.nav");
  const navLinks = document.querySelectorAll("#header nav.nav .nav__link");
  const navmenu = document.querySelector("header#header .navmenu");
  const navmenuList = document.querySelectorAll(
    "header#header .navmenu .navmenu__list"
  );

  const sitemap = document.querySelector("#sitemap");
  const sitemapOpeners = [
    nav.querySelector(".nav__gnb"),
    sitemap.querySelector(".sitemap__close"),
  ];

  sitemapOpeners.forEach((items) => {
    items.addEventListener("click", (event) => {
      event.preventDefault;
      const docBody = document.querySelector("body");
      let currBodyStatus = docBody.classList.contains("noscroll");
      let currStatus = sitemap.classList.contains("sitemap__is-visible");
      currBodyStatus
        ? docBody.classList.remove("noscroll")
        : docBody.classList.add("noscroll");
      currStatus
        ? sitemap.classList.remove("sitemap__is-visible")
        : sitemap.classList.add("sitemap__is-visible");
    });
  });

  
  // 메뉴 위치 계산을 한 번만 수행하고 캐싱하기
  const navmenuListPos = (itemIdx, targetNavmenuList) => {
    // 최초 한 번만 계산하고 캐시
    let elemWdL = navLinks[itemIdx].querySelector("a").getBoundingClientRect().left;
    targetNavmenuList.style.left = elemWdL + "px";
  };

  // 기존 코드를 그대로 유지하되, 로딩 체크 로직 제거
  navLinks.forEach((item, index) => {
    const itemIdx = index + 1;
    const targetNavmenuList = navmenu.querySelector(".navmenu__list:nth-of-type(" + itemIdx + ")");
    const targetNavLink = nav.querySelector(".nav__link:nth-of-type(" + itemIdx + ")");

    // 초기 위치 계산
    navmenuListPos(index, targetNavmenuList);

    // 윈도우 리사이즈 시 메뉴 위치 재계산 (디바운스 처리)
    window.addEventListener("resize", debounce(() => {
      navmenuListPos(index, targetNavmenuList);
    }));

    // mouseenter 시 메뉴 표시 (mouseenter로 변경하여 성능 최적화)
    item.addEventListener("mouseenter", () => {
      // 로딩 체크 로직 제거 - 항상 동작하도록 수정
      if (itemIdx > 5 || itemIdx === 1) {
        navmenu.style.top = 0;
      } else {
        targetNavLink.classList.add("located");
        navmenu.style.top = "calc(100% + 1px)";
        requestAnimationFrame(() => {
          targetNavmenuList.style.display = "flex"; // CSS 트랜지션으로 변경 가능
        });
      }
    });

    // mouseleave 시 메뉴 숨기기
    item.addEventListener("mouseleave", () => {
      // 로딩 체크 로직 제거 - 항상 동작하도록 수정
      targetNavmenuList.style.display = "none";
      navmenu.style.top = 0;
      targetNavLink.classList.remove("located");
    });

    // 서브 메뉴에서 mouseenter 시 메뉴 표시
    targetNavmenuList.addEventListener("mouseenter", () => {
      // 로딩 체크 로직 제거 - 항상 동작하도록 수정
      targetNavLink.classList.add("located");
      navmenu.style.top = "calc(100% + 1px)";
      targetNavmenuList.style.display = "flex";
    });

    // 서브 메뉴에서 mouseleave 시 메뉴 숨기기
    targetNavmenuList.addEventListener("mouseleave", () => {
      // 로딩 체크 로직 제거 - 항상 동작하도록 수정
      targetNavLink.classList.remove("located");
      navmenu.style.top = 0;
      targetNavmenuList.style.display = "none";
    });
  });

  // debounce 함수 (디바운싱 적용)
  function debounce(func, wait = 50) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  //subTab
  const subTabLinks = document.querySelectorAll(".sub-tabs a");
  const subContainer = document.querySelectorAll(".sub-container");

  subTabLinks.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      document.querySelectorAll(".sub-container").forEach((target) => {
        target.classList.remove("sub-container--located");
      });
      for (let i = 0; i < subContainer.length; i++) {
        if (subContainer[i].dataset.content === item.innerText) {
          subContainer[i].classList.add("sub-container--located");
        }
      }
      subTabLinks.forEach((element) => {
        element.classList.contains("sub-tabs__item--active")
          ? element.classList.remove("sub-tabs__item--active")
          : null;
      });
      item.classList.add("sub-tabs__item--active");
    });
  });

  //accordion
  const accordionItems = document.querySelectorAll(
    ".accordian:not(.toggle-accordian) .accordian__item"
  );

  accordionItems.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      item.classList.contains("accordian__item--active")
        ? item.classList.remove("accordian__item--active")
        : item.classList.add("accordian__item--active");
    });
  });

  const toggleAccordionItems = document.querySelectorAll(
    ".accordian.toggle-accordian .accordian__item"
  );
  const toggleAccordionToggle = document.querySelector(
    ".accordian.toggle-accordian .accordian__item .accordian__title"
  );

  toggleAccordionItems.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      return;
    });
    toggleAccordionToggle.addEventListener("click", (event) => {
      event.preventDefault();
      item.classList.contains("accordian__item--active")
        ? item.classList.remove("accordian__item--active")
        : item.classList.add("accordian__item--active");
    });
  });

  //subCateTab
  const subCateTabLinks = document.querySelectorAll(
    ".sub-list-category__default p a"
  );
  const subCateContainer = document.querySelectorAll(
    ".sub-list-category-container"
  );

  subCateTabLinks.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      document
        .querySelectorAll(".sub-list-category-container")
        .forEach((target) => {
          target.classList.remove("sub-list-category-container--located");
        });
      for (let i = 0; i < subCateContainer.length; i++) {
        if (subCateContainer[i].dataset.content === item.innerText) {
          subCateContainer[i].classList.add(
            "sub-list-category-container--located"
          );
        }
      }
      subCateTabLinks.forEach((element) => {
        element.classList.contains("sub-list-category__item--active")
          ? element.classList.remove("sub-list-category__item--active")
          : null;
      });
      item.classList.add("sub-list-category__item--active");
    });
  });

  //subIN-PageTab
  const subInpageTabLinks = document.querySelectorAll(
    ".sub-list-category__inpage p a"
  );

  subInpageTabLinks.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      subInpageTabLinks.forEach((element) => {
        element.classList.contains("sub-list-category__item--active")
          ? element.classList.remove("sub-list-category__item--active")
          : null;
      });
      item.classList.add("sub-list-category__item--active");
      for (let i = 0; i < accordionItems.length; i++) {
        accordionItems[i].classList.remove("accordian__item--active");
        if (
          accordionItems[i].querySelector(".accordian__title > a").innerText ===
          item.innerText
        ) {
          accordionItems[i].classList.add("accordian__item--active");
        }
      }
    });
  });

  //sublistItemsAccordion
  const sublistItems = document.querySelectorAll(".sub-list .sub-list__item");
  const sublistItemTitles = document.querySelectorAll(
    ".sub-list .sub-list__item .sub-list__title"
  );
  const subCateItems = document.querySelectorAll(
    ".sub-list-category__inpage .sub-list-category__item"
  );

  const CleanTargetClassName = () => {
    sublistItems.forEach((targets) => {
      targets.classList.remove("sub-list__item--active");
    });
    subCateItems.forEach((targets) => {
      targets.classList.remove("sub-list-category__item--active");
    });
  };

  sublistItemTitles.forEach((item) => {
    item.addEventListener("click", () => {
      let result1 = item.parentElement.classList.contains(
        "sub-list__item--active"
      );
      let targetChapter = item.parentElement.classList[1].split("--")[1];
      let targetCate = document.querySelector(
        ".sub-list-category__item--" + targetChapter
      );
      let result2 = targetCate.classList.contains(
        "sub-list-category__item--active"
      );

      if (result1 && result2) {
        CleanTargetClassName();
      } else {
        CleanTargetClassName();
        item.parentElement.classList.add("sub-list__item--active");
        targetCate.classList.add("sub-list-category__item--active");
      }
    });
  });

  subCateItems.forEach((item) => {
    item.addEventListener("click", () => {
      let result1 = item.classList.contains("sub-list-category__item--active");
      let targetChapter = item.classList[1].split("--")[1];
      let targetListItem = document.querySelector(
        ".sub-list__item--" + targetChapter
      );
      let result2 = targetListItem.classList.contains("sub-list__item--active");

      if (result1 && result2) {
        CleanTargetClassName();
      } else {
        CleanTargetClassName();
        item.classList.add("sub-list-category__item--active");
        targetListItem.classList.add("sub-list__item--active");
      }
    });
  });

  //sublistItemsAccordion ClickToScroll
  for (let i = 0; i < sublistItems.length; i++) {
    let abTtargetItem = [];

    const ClickToScroll = () => {
      abTtargetItem[i] = parseFloat(sublistItemTitles[i].offsetTop - 90);
      window.scrollTo({ top: abTtargetItem[i], behavior: "smooth" });
    };

    subCateItems[i].addEventListener("click", () => {
      ClickToScroll();
    });
    sublistItemTitles[i].addEventListener("click", () => {
      ClickToScroll();
    });
  }

  //inputButton
  const inputButton = document.querySelectorAll(".input__button");

  inputButton.forEach((item) => {
    item.addEventListener("click", () => {
      if (item.classList.contains("input__button--active"))
        item.classList.remove("input__button--active");
      else item.classList.add("input__button--active");
    });
  });

  //dropdown
  const dropBox = document.querySelectorAll(".drop-box");
  const dropBoxTitle = document.querySelectorAll(".drop-box__title");
  const dropBoxItem = document.querySelectorAll(".drop-box__wrap p > a");
  let dropdownActiveBoolean;

  dropBoxTitle.forEach((item) => {
    let closestTarget = item.parentNode;

    document.body.addEventListener(
      "click",
      () => {
        if (closestTarget.classList.contains("drop-box--active")) {
          closestTarget.classList.remove("drop-box--active");
        }
      },
      true
    );

    item.addEventListener("click", (event) => {
      event.preventDefault();
      dropdownActiveBoolean =
        closestTarget.classList.contains("drop-box--active");

      if (!dropdownActiveBoolean) {
        closestTarget.classList.add("drop-box--active");
      } else {
        closestTarget.classList.remove("drop-box--active");
      }
    });
  });

  dropBoxItem.forEach((item) => {
    let closestTarget = item.closest(".drop-box");

    item.addEventListener("click", (event) => {
      event.preventDefault();
      dropdownActiveBoolean =
        closestTarget.classList.contains("drop-box--active");

      item
        .closest(".drop-box__wrap")
        .parentNode.querySelector(
          ".drop-box__title .drop-box__text"
        ).innerHTML = item.innerHTML;
      closestTarget.classList.remove("drop-box--active");
    });
  });
});

//Input * Form * Swiper
const bodyClass = document.querySelector("body").classList[0];

if (bodyClass) {
  const formFileButton = document.querySelector("#form-file-button");
  const formFileIput = document.querySelector("#form-file-text");

  const formSubmit = document.querySelector(".form-submit");
  const needCheck = document.querySelector("#sub-checkbox--personal");

  const ButtonCloses = document.querySelectorAll(".close");

  //File-Attaching Button
  const InputFileAttaching = () => {
    if (formFileButton === null) return;
    else
      formFileButton.addEventListener("change", () => {
        if (!formFileIput.value) {
          formFileIput.closest(".input-box--file").classList.remove("warning");
        }
        formFileIput.value = formFileButton.files[0].name;
      });
  };

  //Necessary Check
  const CheckboxNeededCheck = () => {
    if (needCheck === null) return;
    else
      needCheck.addEventListener("change", () => {
        needCheck.checked
          ? formSubmit.classList.add("submit--active")
          : formSubmit.classList.remove("submit--active");
      });
  };

  //All .close UI
  const LayerCloseClicker = () => {
    if (ButtonCloses === null) return;
    else
      ButtonCloses.forEach((item) => {
        item.addEventListener("click", (event) => {
          let CurrentPopup = document
            .querySelector(".popup.is-visible")
            .getAttribute("id");
          layerClose(CurrentPopup);
        });
      });
  };

  switch (bodyClass) {
    case "main":
      {
        const swiperOptions = {
          loop: true,
          spaceBetween: 20,
          autoplay: {
            delay: 1,
            disableOnInteraction: false,
          },
          slidesPerView: "auto",
          speed: 12000,
          grabCursor: true,
          mousewheelControl: true,
          keyboardControl: true,
          observer: true,
          observeParents: true,
        };
        const swiper = new Swiper("#swiper", swiperOptions);
      }
      break;

    case "contact":
      {
        window.addEventListener("load", () => {
          InputFileAttaching();
          CheckboxNeededCheck();
          LayerCloseClicker();
        });

        const formUserDetail = document.querySelector("#form-detail");
        const formUserCompany = document.querySelector("#user-company");
        const formUserName = document.querySelector("#user-name");
        const formUserNumber = document.querySelector("#user-number");
        const formUserMail = document.querySelector("#user-mail");
        const formUserUrl = document.querySelector("#user-url");
        const regEmail =
          /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

        const etcTextarea = document.querySelector(".input-box--textarea"); //20230509

        formSubmit.addEventListener("click", (event) => {
          event.preventDefault();

          document.querySelectorAll(".form-field").forEach((item) => {
            item.classList.remove("warning");
          });
          document
            .querySelector(".input-box--file")
            .classList.remove("warning");

          if (!needCheck.checked) {
            needCheck.focus();
            return false;
          }

          if (formUserDetail.value === "") {
            formUserDetail.focus();
            formUserDetail.closest(".input-box--file").classList.add("warning");
            return false;
          }

          if (!formUserCompany.value) {
            formUserCompany.focus();
            formUserCompany.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserName.value) {
            formUserName.focus();
            formUserName.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserNumber.value) {
            formUserNumber.focus();
            formUserNumber.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserMail.value) {
            formUserMail.focus();
            formUserMail.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!regEmail.test(formUserMail.value)) {
            formUserMail.focus();
            formUserMail.closest(".form-field").classList.add("warning");
            formUserMail.nextElementSibling.innerHTML = "질못된 입력값입니다";
            return false;
          }

          if (!formUserUrl.value) {
            formUserUrl.focus();
            formUserUrl.closest(".form-field").classList.add("warning");
            return false;
          }

          layerOpen("formSubmitted");
        });

        //20230509
        document
          .querySelector(".input__checkbox-label--etc")
          .addEventListener("click", () => {
            etcTextarea.classList.toggle("open");
          });
				//20240417
				document
          .querySelector(".showbutton--marketing")
          .addEventListener("click", (event) => {
            event.preventDefault();
            layerOpen("marketing");
          });

        document
          .querySelector(".showbutton--personal")
          .addEventListener("click", (event) => {
            event.preventDefault();
            layerOpen("personalTerms");
          });
      }
      break;

    case "ethical":
      {
        //File-Attaching Button
        const InputFileAttaching = () => {
          if (formFileButton === null) return;
          else
            formFileButton.addEventListener("change", () => {
              if (!formFileIput.value) {
                formFileIput
                  .closest(".input-box--file")
                  .classList.remove("warning");
              }
              formFileIput.value = formFileButton.files[0].name;
            });
        };

        //Necessary Check
        // const CheckboxNeededCheck = () => {
        // 	if (needCheck === null) return;
        // 	else needCheck.addEventListener('change', () => {
        // 		needCheck.checked ? formSubmit.classList.add('submit--active') : formSubmit.classList.remove('submit--active');
        // 	})
        // }

        //All .close UI
        const LayerCloseClicker = () => {
          if (ButtonCloses === null) return;
          else
            ButtonCloses.forEach((item) => {
              item.addEventListener("click", (event) => {
                let CurrentPopup = document
                  .querySelector(".popup.is-visible")
                  .getAttribute("id");
                layerClose(CurrentPopup);
              });
            });
        };

        InputFileAttaching();
        // CheckboxNeededCheck();
        LayerCloseClicker();

        const popLayers = document.querySelectorAll(
          ".open.sub-content__title--link"
        );

        popLayers.forEach((item) => {
          item.addEventListener("click", (event) => {
            layerOpen("termAgreePrivacy");
          });
        });

        //Goto

        const targetGoto1 = document.querySelector(
          '[data-content="윤리신고 제도 안내"] .button[data-button="goto"]'
        );
        if(targetGoto1) {

          targetGoto1.addEventListener("click", (e) => {
            e.preventDefault();
            const targetClassName1 = `sub-list-category-container--located`;
            const targetClassName2 = `sub-list-category__item--active`;

            document
              .querySelectorAll(".sub-list-category-container")
              .forEach((item) => {
                item.classList.remove(targetClassName1);
              });

            const listBox1 = document.querySelector(
              '[data-content="윤리경영 신고"] .sub-list-category__box > p:nth-of-type(1) a'
            );
            const listBox2 = document.querySelector(
              '[data-content="윤리경영 신고"] .sub-list-category__box > p:nth-of-type(2) a'
            );

            document
              .querySelector(
                '.sub-list-category-container[data-content="제보하기"]'
              )
              .classList.add(targetClassName1);
            listBox1.classList.remove(targetClassName2);
            listBox2.classList.add(targetClassName2);

            window.scrollTo({
              top: document.querySelector(".sub-tabs").offsetTop - 40,
              behavior: "smooth",
            });
          });

        }

        const targetGoto2 = document.querySelector(
          '[data-content="윤리신고 제도 안내"] .sub-content__title--link[data-button="goto"]'
        );
        if(targetGoto2) {
          targetGoto2.addEventListener("click", (e) => {
            e.preventDefault();
            const targetClassName0 = `sub-container--located`;
            const targetClassName1 = `sub-tabs__item--active`;
            const targetClassName2 = `sub-list-category__item--active`;
            const targetClassName3 = `sub-list__item--active`;

            document.querySelectorAll(".sub-container").forEach((item) => {
              item.classList.remove(targetClassName0);
            });

            const targetContainer = document.querySelector(
              '.sub-container[data-content="윤리경영 실천 지침"]'
            );

            targetContainer.classList.add(targetClassName0);

            const target1 = document.querySelector(
              ".sub-tabs .sub-tabs__item:nth-of-type(2)"
            );
            const target2 = document.querySelector(
              '[data-content="윤리경영 실천 지침"] .sub-list-category__box > p:nth-of-type(6) a'
            );
            const target3 = document.querySelector(
              '.sub-container[data-content="윤리경영 실천 지침"] .sub-list__item--chapter-6 '
            );

            document
              .querySelectorAll(".sub-tabs .sub-tabs__item")
              .forEach((item) => {
                item.classList.remove(targetClassName1);
              });

            document
              .querySelectorAll(
                '[data-content="윤리경영 실천 지침"] .sub-list-category__box > p a'
              )
              .forEach((item) => {
                item.classList.remove(targetClassName2);
              });

            document
              .querySelectorAll(
                '[data-content="윤리경영 실천 지침"] .sub-list__item'
              )
              .forEach((item) => {
                item.classList.remove(targetClassName3);
              });

            target1.classList.add(targetClassName1);
            target2.classList.add(targetClassName2);
            target3.classList.add(targetClassName3);

            window.scrollTo({
              top: document.querySelector(".sub-tabs").offsetTop + 1905,
              behavior: "smooth",
            });
          });
        }

      }
      break;

    case "letter":
      {
        window.addEventListener("load", () => {
          CheckboxNeededCheck();
          LayerCloseClicker();
        });

        const formOpener = document.querySelector(".open");
        const formSubmitButton = document.querySelector(".form-submit");
        const formUserName = document.querySelector("#user-name");
        const formUserProfession = document.querySelector("#user-profession");
        const formUserMail = document.querySelector("#user-mail");
        const regEmail =
          /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

        // formSubmit.addEventListener('click' , () => {
        // 	layerOpen('newsLetter');
        // });

        document
          .querySelector("#letterFormSubmitted .layer__button.close")
          .addEventListener("click", () => {
            layerClose("newsLetter");
          });

        // formOpener.addEventListener('click' , (event) => {
        // 	event.preventDefault();
        // 	layerOpen('newsLetter');
        // });

        formSubmitButton.addEventListener("click", (event) => {
          event.preventDefault();
          document.querySelectorAll(".form-field").forEach((item) => {
            item.classList.remove("warning");
          });

          if (!needCheck.checked) {
            needCheck.focus();
            return false;
          }

          if (!formUserName.value) {
            formUserName.focus();
            formUserName.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserProfession.value) {
            formUserProfession.focus();
            formUserProfession.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserMail.value) {
            formUserMail.focus();
            formUserMail.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!regEmail.test(formUserMail.value)) {
            formUserMail.focus();
            formUserMail.closest(".form-field").classList.add("warning");
            formUserMail.nextElementSibling.innerHTML = "질못된 입력값입니다";
            return false;
          }

          layerOpen("letterFormSubmitted");
        });
      }
      break;

    case "article":
      {
        (function () {
          let options = {};
          let slides = document.querySelectorAll("#swiper .swiper-slide");
          let navis = document.querySelectorAll(
            "#swiper .swiper-button-prev, #swiper .swiper-button-next"
          );
          if (slides.length == 1) {
            (swiperOptions = {
              loop: false,
              spaceBetween: 0,
              slidesPerView: "1",
              speed: 0,
              grabCursor: false,
              mousewheelControl: false,
              keyboardControl: false,
            }),
              navis.forEach((item) => {
                item.style.display = `none`;
              });
          } else {
            swiperOptions = {
              loop: false,
              spaceBetween: 0,
              slidesPerView: "1",
              speed: 400,
              grabCursor: true,
              mousewheelControl: true,
              keyboardControl: true,
              observer: true,
              observeParents: true,
              navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
              },
              pagination: {
                el: ".swiper-pagination",
                type: "fraction",
              },
            };
          }
          const swiper = new Swiper("#swiper", swiperOptions);
        })();

        window.addEventListener("load", () => {
          CheckboxNeededCheck();
          LayerCloseClicker();
        });

        const formOpenersLetterDetil =
          document.querySelectorAll("#LetterDetil .open");
        const formOpenersdownloadOpen =
          document.querySelectorAll(".downloadOpen");
        const formSubmitButtonnewsLetter = document.querySelector(
          "#newsLetter .form-submit"
        );
        const formSubmitButtonreportDownload = document.querySelector(
          "#reportDownload .form-submit"
        );
        const formUserName = document.querySelector("#user-name");
        const formUserProfession = document.querySelector("#user-profession");
        const formUserMail = document.querySelector("#user-mail");
        const regEmail =
          /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

        const ActionFormOpenersLetterDetil = () => {
          if (formOpenersLetterDetil === null) {
            return;
          } else {
            formOpenersLetterDetil.forEach((item) => {
              item.addEventListener("click", (event) => {
                event.preventDefault();
                layerOpen("newsLetter");
              });
            });
          }
        };
        ActionFormOpenersLetterDetil();

        const ActionFormOpenersdownloadOpen = () => {
          if (formOpenersdownloadOpen === null) {
            return;
          } else {
            formOpenersdownloadOpen.forEach((item) => {
              item.addEventListener("click", (event) => {
                event.preventDefault();
                layerOpen("reportDownload");
              });
            });
          }
        };
        ActionFormOpenersdownloadOpen();

        const ActionFormSubmitButtonnewsLetter = () => {
          if (formSubmitButtonnewsLetter === null) {
            return;
          } else {
            formSubmitButtonnewsLetter.addEventListener("click", (event) => {
              event.preventDefault();
              document.querySelectorAll(".form-field").forEach((item) => {
                item.classList.remove("warning");
              });

              if (!needCheck.checked) {
                needCheck.focus();
                return false;
              }

              if (!formUserName.value) {
                formUserName.focus();
                formUserName.closest(".form-field").classList.add("warning");
                return false;
              }

              if (!formUserProfession.value) {
                formUserProfession.focus();
                formUserProfession
                  .closest(".form-field")
                  .classList.add("warning");
                return false;
              }

              if (!formUserMail.value) {
                formUserMail.focus();
                formUserMail.closest(".form-field").classList.add("warning");
                return false;
              }

              if (!regEmail.test(formUserMail.value)) {
                formUserMail.focus();
                formUserMail.closest(".form-field").classList.add("warning");
                formUserMail.nextElementSibling.innerHTML =
                  "질못된 입력값입니다";
                return false;
              }

              layerOpen("letterFormSubmitted");

              document
                .querySelector("#letterFormSubmitted .layer__button.close")
                .addEventListener("click", () => {
                  layerClose("newsLetter");
                  layerClose("letterFormSubmitted");
                });
            });
          }
        };
        ActionFormSubmitButtonnewsLetter();

        const ActionFormSubmitButtonreportDownload = () => {
          if (formSubmitButtonreportDownload === null) {
            return;
          } else {
            formSubmitButtonreportDownload.addEventListener(
              "click",
              (event) => {
                event.preventDefault();
                document.querySelectorAll(".form-field").forEach((item) => {
                  item.classList.remove("warning");
                });

                if (!needCheck.checked) {
                  needCheck.focus();
                  return false;
                }

                if (!formUserName.value) {
                  formUserName.focus();
                  formUserName.closest(".form-field").classList.add("warning");
                  return false;
                }

                if (!formUserProfession.value) {
                  formUserProfession.focus();
                  formUserProfession
                    .closest(".form-field")
                    .classList.add("warning");
                  return false;
                }

                if (!formUserMail.value) {
                  formUserMail.focus();
                  formUserMail.closest(".form-field").classList.add("warning");
                  return false;
                }

                if (!regEmail.test(formUserMail.value)) {
                  formUserMail.focus();
                  formUserMail.closest(".form-field").classList.add("warning");
                  formUserMail.nextElementSibling.innerHTML =
                    "질못된 입력값입니다";
                  return false;
                }

                layerClose("reportDownload");
              }
            );
          }
        };
        ActionFormSubmitButtonreportDownload();
      }
      break;

    case "report":
      {
        window.addEventListener("load", () => {
          CheckboxNeededCheck();
          LayerCloseClicker();
        });

        const formOpeners = document.querySelectorAll(".open");
        const formSubmitButton = document.querySelector(".form-submit");
        const formUserName = document.querySelector("#user-name");
        const formUserProfession = document.querySelector("#user-profession");
        const formUserMail = document.querySelector("#user-mail");

        const openPersonal = document.querySelector('.open__personal');
        const closePersonal = document.querySelectorAll('.popup.personalPopup .closePopup');
        const openMarketing = document.querySelector('.open__marketing');
        const closeMarketing = document.querySelectorAll('.popup.marketingPopup .closePopup');

        const regEmail =
          /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

        formOpeners.forEach((item) => {
          item.addEventListener("click", (event) => {
            event.preventDefault();
            layerOpen("reportDownload");
          });
        });

        formSubmitButton.addEventListener("click", (event) => {
          event.preventDefault();

          document.querySelectorAll(".form-field").forEach((item) => {
            item.classList.remove("warning");
          });

          if (!needCheck.checked) {
            needCheck.focus();
            return false;
          }

          if (!formUserName.value) {
            formUserName.focus();
            formUserName.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserProfession.value) {
            formUserProfession.focus();
            formUserProfession.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!formUserMail.value) {
            formUserMail.focus();
            formUserMail.closest(".form-field").classList.add("warning");
            return false;
          }

          if (!regEmail.test(formUserMail.value)) {
            formUserMail.focus();
            formUserMail.closest(".form-field").classList.add("warning");
            formUserMail.nextElementSibling.innerHTML = "질못된 입력값입니다";
            return false;
          }

          layerClose("reportDownload");
        });

        // 2024.05.02
        // openPersonal.addEventListener("click", (event) => {
        //   event.preventDefault();
        //   layerOpen('personalPopup');
        // });

        // closePersonal.forEach((item) => {
        //   item.addEventListener("click", (event) => {
        //     event.preventDefault();
        //     layerClose('personalPopup');
        //   });
        // });

        // openMarketing.addEventListener("click", (event) => {
        //   event.preventDefault();
        //   layerOpen('marketingPopup');
        // });

        // closeMarketing.forEach((item) => {
        //   item.addEventListener("click", (event) => {
        //     event.preventDefault();
        //     layerClose('marketingPopup');
        //   });
        // });
        // //2024.05.02
      }
      break;

    case "welfare":
      {
        (function () {
          let options = {};
          let slides = document.querySelectorAll("#swiper .swiper-slide");
          if (slides.length == 1) {
            swiperOptions = {
              loop: false,
              spaceBetween: 0,
              slidesPerView: "1",
              speed: 0,
              grabCursor: false,
              mousewheelControl: false,
              keyboardControl: false,
            };
          } else {
            swiperOptions = {
              loop: true,
              spaceBetween: 0,
              slidesPerView: "1",
              speed: 400,
              grabCursor: true,
              mousewheelControl: true,
              keyboardControl: true,
              observer: true,
              observeParents: true,
              autoplay: true,
              pagination: {
                el: ".swiper-pagination",
                type: "bullets",
                clickable: true,
              },
            };
          }
          const swiper = new Swiper("#swiper", swiperOptions);
        })();
      }
      break;

    case "esg":
      {
        let wdH = window.innerHeight;
        const area = document.querySelector('.sub-content[data-section="ESG"]');
        const areaContImgs = area.querySelectorAll(".image-block");

        window.addEventListener("resize", () => {
          wdH = window.innerHeight;
        });

        window.addEventListener("scroll", () => {
          let scT = window.pageYOffset;
          for (i = 0; i < areaContImgs.length; i++) {
            if (areaContImgs[i].getBoundingClientRect().top <= scT * 0.8) {
              areaContImgs[i].classList.add("slidein");
            }
          }
        });
      }
      break;

    case "campaign":
      {
        const visual = document.querySelector(".visual");
        const visualLists = document.querySelectorAll(".visual__text li");

        window.addEventListener("load", () => {
          visual.classList.add("loaded");
          visualLists.forEach((item) => {
            item.classList.add("loaded");
          });
        });

        visualLists.forEach((item) => {
          item.addEventListener("mouseenter", () => {
            item.classList.add("hovered");
          });
          item.addEventListener("mouseleave", () => {
            item.classList.remove("hovered");
          });
        });
      }
      break;

    case "ir":
      {
        const formSubmitOpen = document.querySelector(".sub-link");
        const formSubmitClose = document.querySelector(".layer__close");
        const formSubmitCloseCorfirm = document.querySelector(".layer__button");

        formSubmitOpen.addEventListener("click", (event) => {
          event.preventDefault();
          layerOpen("DisclosureInformation");
        });

        formSubmitClose.addEventListener("click", () => {
          layerClose("DisclosureInformation");
        });

        formSubmitCloseCorfirm.addEventListener("click", () => {
          layerClose("DisclosureInformation");
        });
      }
      break;
		
		case "creative":
			if (document.querySelectorAll(".swiper-slide").length > 1) {
				const swiper = new Swiper("#creative_swiper", {
					slidesPerView: 1,
					spaceBetween: 0,
					observer: true,
					observeParents: true,
					pagination: {
						el: ".swiper-pagination",
						type: "bullets",
						clickable: true,
					},
					// loop: true,
				});
			}

			break;

    case "about":
      {
        const header = document.querySelector(".sub-header");
        const targetImage = document.querySelector(".visual__image");
        const targetBox = document.querySelector(".visual__box");

        window.addEventListener("scroll", () => {
          let sct = window.pageYOffset;

          if (sct > 70) {
            header.classList.add("approached");
            targetImage.style.transformOrigin = `bottom left`;
            targetBox.style.transitionDelay = `0.5s`;
          }
          if (sct === 0) {
            header.classList.remove("approached");
            targetImage.style.transformOrigin = `top left`;
            targetBox.style.transitionDelay = `0s`;
          }
        });

        if (document.querySelectorAll(".sub-slide .swiper-slide").length > 1) {
          const stateBar = document.querySelector(".state-bar");
          const stateBarFill = document.querySelector(".state-bar--fill");
          let stateBarWidth = stateBar.clientWidth + 360;

          const swiper = new Swiper(".sub-slide ", {
            slidesPerView: 2,
            spaceBetween: 80,
            observer: true,
            observeParents: true,
            navigation: {
              nextEl: ".swiper__navi .swiper__button-next",
              prevEl: ".swiper__navi .swiper__button-prev",
            },
            // loop: true,
            on: {
              init: function () {
                stateBarFill.style.width =
                  stateBarWidth /
                    document.querySelectorAll(".sub-slide .swiper-slide")
                      .length +
                  "px";
              },
            },
          });

          swiper.on("slideChange", function (e) {
            let targetFill =
              (stateBarWidth /
                document.querySelectorAll(".sub-slide .swiper-slide").length) *
              (swiper.realIndex + 1);
            if (targetFill == 1300) {
              targetFill = 4000;
            }
            stateBarFill.style.width = targetFill + "px";
          });
        }

        const targetFocuses = document.querySelectorAll(".a11y__scroll");

        targetFocuses.forEach((item) => {
          item.addEventListener("focusin", () => {
            window.scrollTo({
              top: item.parentNode.offsetTop + 400,
              behavior: "smooth",
            });
          });
        });
      }
      break;

    case "personal":
      {
        const categoryItem = document.querySelectorAll(".term-anchor li a");
        categoryItem.forEach((item) => {
          item.addEventListener("click", (event) => {
            event.preventDefault();
            window.scrollTo({
              top:
                document.querySelector(
                  ".term-anchor__item--" + item.dataset.content
                ).offsetTop - 50,
              behavior: "smooth",
            });
          });
        });
      }
      break;

    default:
      break;
  }
}

//layerPopup
let isOpen = false;
const layerOpen = (layerId) => {
  if (document.querySelector("#" + layerId) == null) return;
  let curPos = window.pageYOffset;
  document.documentElement.classList.add("noscroll");
  document.querySelector("#" + layerId).classList.add("is-visible");
  let layerID = document.querySelector("#" + layerId);
  layerID.setAttribute("aria-hidden", "false");
  if (document.querySelector('[role="dialog"].is-visible') && isOpen == false) {
    isOpen = true;
  }
  const delay = setTimeout(function () {
    if (!document.documentElement.classList.contains("noscroll")) {
      document.documentElement.classList.add("noscroll");
    }
    clearTimeout(delay);
  }, 50);
};
const layerClose = (layerId) => {
  if (document.querySelector("#" + layerId) == null) return;
  let curPos = -parseInt(document.querySelector(".popup").pageYOffset);
  document.querySelector("#" + layerId).classList.remove("is-visible");
  document.querySelector("#" + layerId).setAttribute("aria-hidden", "true");
  document.documentElement.classList.remove("noscroll");
  if (document.querySelector('[role="dialog"].is-visible')) {
    document.documentElement.classList.remove("noscroll");
    window.scrollTop = curPos;
    isOpen = false;
  }
};

var divDisplay = true;
function doDisplay() {
  var con = document.getElementById("marketing-div");
  var frm = document.getElementById("marketing-frm");
  var frbtm = document.querySelector(".sub-checkbox_btn");

  if (con.style.display == "block") {
    frm.classList.add("open");
    frbtm.classList.remove("open");
    con.style.display = "none";
  } else {
    con.style.display = "block";
    frm.classList.remove("open");
    frbtm.classList.add("open");
  }
}
