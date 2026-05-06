(function () {
  //stickyHeader
  const header = document.querySelector("#header");
  const bodyClass = document.querySelector("body").classList[0];
  const headerTitle = document.querySelector(".header__title");
  const footerBanner = document.querySelector(".footer__banner");

  const subCommonFunction = () => {
    header.classList.add("scrolled");
    headerTitle.classList.add("sub-page--active");
    headerTitle.classList.add("font--md");
    header.classList.add("scrolled");
    footerBanner.classList.add("sub-page--active");
  };

  if (bodyClass) {
    switch (bodyClass) {
      case "main":
        window.addEventListener("scroll", () => {
          let sct = window.pageYOffset;
          let threshold = 10;
          sct > threshold
            ? header.classList.add("scrolled")
            : header.classList.remove("scrolled");
        });
        break;

      case "contact":
        headerTitle.innerHTML = bodyClass.toUpperCase();
        subCommonFunction();
        break;

      case "recruit":
        headerTitle.innerHTML = "채용";
        subCommonFunction();
        break;

      case "welfare":
        headerTitle.innerHTML = "복리후생";
        subCommonFunction();
        break;

      case "ethical":
        headerTitle.innerHTML = "윤리경영";
        subCommonFunction();
        break;

      case "letter":
        headerTitle.innerHTML = "뉴스레터";
        subCommonFunction();
        break;

      case "letter-detail":
        headerTitle.innerHTML = "뉴스레터";
        subCommonFunction();
        break;

      case "report":
        headerTitle.innerHTML = "리포트";
        subCommonFunction();
        break;

      case "report-detail":
        headerTitle.innerHTML = "리포트";
        subCommonFunction();
        break;

      case "column":
        headerTitle.innerHTML = "광고 칼럼";
        subCommonFunction();
        break;

      case "column-detail":
        headerTitle.innerHTML = "광고 칼럼";
        subCommonFunction();
        break;

      case "ir":
        headerTitle.innerHTML = "IR";
        subCommonFunction();
        break;

      case "ir-detail":
        headerTitle.innerHTML = "IR";
        subCommonFunction();
        break;

      case "portfolio":
        headerTitle.innerHTML = "포트폴리오";
        subCommonFunction();
        break;

      case "techhub":
        headerTitle.innerHTML = "techHUB";
        subCommonFunction();
        break;

      case "awards":
        headerTitle.innerHTML = "수상내역";
        subCommonFunction();
        break;

      case "campaign":
        headerTitle.innerHTML = "캠페인 전략";
        subCommonFunction();
        break;

      case "creative":
        headerTitle.innerHTML = "크리에이티브";
        subCommonFunction();
        break;

      case "esg":
        headerTitle.innerHTML = "ESG";
        subCommonFunction();
        break;

      case "media":
        headerTitle.innerHTML = "미디어전략";
        subCommonFunction();
        break;

      case "about":
        headerTitle.innerHTML = "ABOUT";
        subCommonFunction();
        break;

      case "personal":
        headerTitle.innerHTML = "개인정보 처리방침";
        subCommonFunction();
        break;

      default:
        alert("페이지 오류");
    }
  }

  //gnb
  // const headerGnbItem = document.querySelectorAll('.gnb__item');
  // const headerGnbItemA = document.querySelectorAll('.gnb__item > a');
  // headerGnbItemA.forEach((item) => {
  // 	item.addEventListener('click', (event) => {
  // 		event.preventDefault();
  // 		if(item.closest('div').classList.contains('gnb__item--active')){
  // 			item.closest('div').classList.remove('gnb__item--active')
  // 			return false;
  // 		};

  // 		headerGnbItem.forEach((targets) => {
  // 			targets.classList.remove('gnb__item--active');
  // 		})
  // 		item.closest('div').classList.add('gnb__item--active');
  // 	});
  // })

  const gnbButton = document.querySelector(".header__button");
  const gnb = document.querySelector(".gnb");
  const body = document.querySelector("body");

  gnbButton.addEventListener("click", () => {
    if (body.classList.contains("open-gnb")) {
      body.classList.remove("open-gnb");
    } else {
      gnb.classList.add("gnb--open");
      body.classList.add("open-gnb");
    }
  });

  //input
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
  const dropBoxItem = document.querySelectorAll(".drop-box__wrap p > a");

  document.body.addEventListener(
    "click",
    (event) => {
      let closestTarget = event.target.closest(".drop-box");

      if (closestTarget) {
        if (closestTarget.classList.contains("drop-box--active")) {
          closestTarget.classList.remove("drop-box--active");
        } else {
          dropBox.forEach((item) => {
            if (!item.classList.contains("drop-box--active")) {
              closestTarget.classList.add("drop-box--active");
            }
          });
        }
      }
    },
    true
  );

  dropBoxItem.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      let closestTarget = item.closest(".drop-box");
      item
        .closest(".drop-box__wrap")
        .parentElement.querySelector(".drop-box__title").innerHTML =
        item.innerHTML;

      closestTarget.classList.remove("drop-box--active");
    });
  });

  //tab
  const subTabs = document.querySelector(".sub-tabs__wrap");
  const subTabsA = document.querySelectorAll(".sub-tabs a");
  const subContainer = document.querySelectorAll(".sub-container");

  subTabsA.forEach((item) => {
    item.addEventListener("click", (event, target) => {
      event.preventDefault();

      if (item.dataset.content) {
        document.querySelectorAll(".sub-container").forEach((thisTarget) => {
          thisTarget.classList.remove("content--active");
        });

        document
          .querySelector(".sub-container--" + item.dataset.content)
          .classList.add("content--active");

        subTabsA.forEach((element) => {
          element.classList.contains("sub-tabs__item--active")
            ? element.classList.remove("sub-tabs__item--active")
            : null;
        });

        item.classList.add("sub-tabs__item--active");
      }
    });
  });

  const accordionItem = document.querySelectorAll(".accordian__item .accordian__title");
  accordionItem.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      if (item.closest(".accordian__item").classList.contains("accordian__item--active")) {
        item.closest(".accordian__item").classList.remove("accordian__item--active");
      } else {
        item.closest(".accordian__item").classList.add("accordian__item--active");
      }
    });
  });

  //go-top-button
  window.addEventListener("scroll", () => {
    let sct = window.pageYOffset;

    if (sct > 700) {
      document.querySelector(".top-button").style.display = "block";
      document.querySelector(".top-button a").style.position = "fixed";
      document.querySelector(".top-button a").style.bottom = "5px";
    } else {
      document.querySelector(".top-button").style.display = "none";
    }
  });

  document.querySelector(".top-button").addEventListener("click", (event) => {
    event.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  //form check layerpopup
  if (bodyClass === "contact") {
		// 20240417
    document
      .querySelector(".showbutton--personal")
      .addEventListener("click", (event) => {
        event.preventDefault();
        layerOpen("personalTerms");
      });
    document
      .querySelector(".showbutton--marketing")
      .addEventListener("click", (event) => {
        event.preventDefault();
        layerOpen("marketing");
      });

    document
      .querySelector(".personalTerms .close")
      .addEventListener("click", () => {
        layerClose("personalTerms");
      });

    document
      .querySelector(".personalTerms .layer__footer button")
      .addEventListener("click", () => {
        layerClose("personalTerms");
      });

    document
      .querySelector(".marketing .close")
      .addEventListener("click", () => {
        layerClose("marketing");
      });

    document
      .querySelector(".marketing .layer__footer button")
      .addEventListener("click", () => {
        layerClose("marketing");
      });

    const formSubmit = document.querySelector(".contact .form-submit");
    const formSubmitClose = document.querySelector(".contact .close_btn");
    const formUserCompany = document.querySelector(".contact #user-company");
    const formUserName = document.querySelector(".contact #user-name");
    const formUserNumber = document.querySelector(".contact #user-number");
    const formUserMail = document.querySelector(".contact #user-mail");
    const formUserUrl = document.querySelector(".contact #user-url");
    const formUserDetail = document.querySelector(".contact #form-detail");
    const formFileIput = document.querySelector(".contact #form-file-text");
    const formFileButton = document.querySelector(".contact #form-file-button");
    const needCheck = document.querySelector(
      ".contact #sub-checkbox--personal"
    );
    const regEmail =
      /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

    const etcTextarea = document.querySelector(".input-box--textarea"); //20230509

    needCheck.addEventListener("change", () => {
      if (needCheck.checked) {
        formSubmit.classList.add("submit--active");
      } else {
        formSubmit.classList.remove("submit--active");
      }
    });

    formFileButton.addEventListener("change", () => {
      formFileIput.value = formFileButton.files[0].name;
    });

    formSubmit.addEventListener("click", (event) => {
      event.preventDefault();
      document.querySelectorAll(".form-field").forEach((item) => {
        item.classList.remove("warning");
      });

      document.querySelector(".input-box--file").classList.remove("warning");

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

      layerOpen("form-popup");
    });

    //20230509
    document
      .querySelector(".input__checkbox-label--etc")
      .addEventListener("click", () => {
        etcTextarea.classList.toggle("open");
      });

    formSubmitClose.addEventListener("click", (event) => {
      layerClose("form-popup");
    });
  }

  if (bodyClass === "recruit") {
    const recruitItem = document.querySelectorAll(".recruit-ordinary__item");
    const recruitCloseButton = document.querySelectorAll(".close_btn ");

    recruitItem.forEach((item, index) => {
      item.addEventListener("click", () => {
        layerOpen("recruit-popup--" + [index + 1]);
      });
    });

    recruitCloseButton.forEach((item, index) => {
      item.addEventListener("click", () => {
        layerClose("recruit-popup--" + [index + 1]);
      });
    });
  }

  if (bodyClass === "welfare") {
    const swiper = new Swiper(".swiper", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
  }

  if (bodyClass === "ethical" || bodyClass === "personal") {
    const sublist = document.querySelectorAll(".sub-list__item");
    const categoryItem = document.querySelectorAll(".sub-list-category__item");
    const declarationList = document.querySelectorAll(".sub-content");
    const formSubmitOpen = document.querySelector(
      ".ethical .sub-content__title--link"
    );
    const formSubmitClose = document.querySelector(".ethical .close");
    const formSubmitClose2 = document.querySelector(".ethical .layer__button");

    document.body.addEventListener(
      "click",
      (event) => {
        let closestTarget = event.target.closest(".sub-list__item");

        if (closestTarget) {
          if (closestTarget.classList.contains("sub-list__item--active")) {
            closestTarget.classList.remove("sub-list__item--active");
          } else {
            sublist.forEach((item) => {
              if (!item.classList.contains("sub-list__item--active")) {
                closestTarget.classList.add("sub-list__item--active");
              }
            });
          }
        }
      },
      true
    );

    categoryItem.forEach((item) => {
      item.addEventListener("click", (event) => {
        event.preventDefault();
        sublist.forEach((targets) => {
          targets.classList.remove("sub-list__item--active");
        });

        categoryItem.forEach((targets) => {
          targets.classList.remove("sub-list-category__item--active");
        });

        document
          .querySelector(".sub-list__item--" + item.dataset.content)
          .classList.add("sub-list__item--active");

        window.scrollTo({
          top:
            document.querySelector(".sub-list__item--" + item.dataset.content)
              .offsetTop - 62,
          behavior: "smooth",
        });

        if (
          document
            .querySelector(".sub-container--guideline")
            .classList.contains("content--active")
        ) {
          document
            .querySelector(
              ".sub-container--guideline .sub-list-category__item--" +
                item.dataset.content
            )
            .classList.add("sub-list-category__item--active");
        }
        if (
          document
            .querySelector(".sub-container--declaration")
            .classList.contains("content--active")
        ) {
          document
            .querySelector(
              ".sub-container--declaration .sub-list-category__item--" +
                item.dataset.content
            )
            .classList.add("sub-list-category__item--active");

          declarationList.forEach((targets) => {
            targets.classList.remove("sub-content--active");
          });

          document
            .querySelector(
              ".sub-container--declaration .sub-content--" +
                item.dataset.content
            )
            .classList.add("sub-content--active");
        }
      });
    });

    formSubmitOpen.addEventListener("click", () => {
      layerOpen("termAgreePrivacy");
    });

    formSubmitClose.addEventListener("click", () => {
      layerClose("termAgreePrivacy");
    });

    formSubmitClose2.addEventListener("click", () => {
      layerClose("termAgreePrivacy");
    });

    // document
    //   .querySelector(".compensation")
    //   .addEventListener("click", (event) => {
    //     event.preventDefault();
    //     document.querySelectorAll(".sub-tabs__box p a").forEach((item) => {
    //       item.classList.remove("sub-tabs__item--active");
    //     });
    //     document
    //       .querySelector(".sub-tabs__box p:nth-child(2) a")
    //       .classList.add("sub-tabs__item--active");

    //     document.querySelectorAll(".sub-container").forEach((item) => {
    //       item.classList.remove("content--active");
    //     });
    //     document
    //       .querySelector(".sub-container--guideline")
    //       .classList.add("content--active");

    //     document.querySelectorAll(".sub-list__item").forEach((item) => {
    //       item.classList.remove("sub-list__item--active");
    //     });
    //     document
    //       .querySelector(".sub-list__item--chapter-6")
    //       .classList.add("sub-list__item--active");

    //     document
    //       .querySelectorAll(".sub-list-category__box p a")
    //       .forEach((item) => {
    //         item.classList.remove("sub-list-category__item--active");
    //       });
    //     document
    //       .querySelector(".sub-list-category__box p:nth-child(6) a")
    //       .classList.add("sub-list-category__item--active");

    //     window.scrollTo({
    //       top: document.querySelector("#compensation").offsetTop - 70,
    //       behavior: "smooth",
    //     });
    //   });

    // document.querySelector(".more").addEventListener("click", (event) => {
    //   event.preventDefault();
    //   layerOpen("termAgreePrivacy");
    // });
  }

  if (bodyClass === "letter") {
    const subTabItem = document.querySelectorAll(".sub-tabs__item");
    const formSubmit = document.querySelector(".letter .form-submit--wide");
    const formSubmitOpen = document.querySelector(
      ".letter .button__filled-black"
    );
    const formSubmitClose = document.querySelector(".letter .close");
    const formCheckClose = document.querySelector(".letter .close_btn");
    const formUserName = document.querySelector(".letter #user-name");
    const formUserPosition = document.querySelector(".letter #user-position");
    const formUserMail = document.querySelector(".letter #user-mail");
    const needCheck = document.querySelector(".letter #sub-checkbox--personal");
    const regEmail =
      /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

    subTabItem.forEach((item) => {
      item.addEventListener("click", () => {
        subTabItem.forEach((thisTarget) => {
          if (thisTarget.classList.contains("sub-tabs__item--active"))
            thisTarget.classList.remove("sub-tabs__item--active");
        });
        item.classList.add("sub-tabs__item--active");
      });
    });

    // formSubmitOpen.addEventListener('click' , () => {
    // 	layerOpen('newsLetter');
    // })

    needCheck.addEventListener("change", () => {
      if (needCheck.checked) {
        formSubmit.classList.add("submit--active");
      } else {
        formSubmit.classList.remove("submit--active");
      }
    });

    // formSubmit.addEventListener('click' , (event) => {
    // 	event.preventDefault();
    // 	document.querySelectorAll('.form-field').forEach((item) => {
    // 		item.classList.remove('warning');
    // 	})

    // 	// if(!needCheck.checked) {
    // 	// 	needCheck.focus();
    // 	// 	return false;
    // 	// }

    // 	// if(!formUserName.value) {
    // 	// 	formUserName.focus();
    // 	// 	formUserName.closest('.form-field').classList.add('warning')
    // 	// 	return false;
    // 	// }

    // 	// if(!formUserPosition.value) {
    // 	// 	formUserPosition.focus();
    // 	// 	formUserPosition.closest('.form-field').classList.add('warning')
    // 	// 	return false;
    // 	// }

    // 	// if(!formUserMail.value) {
    // 	// 	formUserMail.focus();
    // 	// 	formUserMail.closest('.form-field').classList.add('warning')
    // 	// 	return false;
    // 	// }

    // 	// if(!regEmail.test(formUserMail.value)) {
    // 	// 	formUserMail.focus();
    // 	// 	formUserMail.closest('.form-field').classList.add('warning')
    // 	// 	formUserMail.nextElementSibling.innerHTML = '질못된 입력값입니다';
    // 	// 	return false;
    // 	// }

    // 	// layerOpen('form-popup');
    // })

    formCheckClose.addEventListener("click", () => {
      layerClose("form-popup");
    });

    formSubmitClose.addEventListener("click", () => {
      layerClose("newsLetter");
    });

    const tabOffsetTop =
      document.querySelector(".sub-tabs").offsetTop - (document.querySelector(".sub-tabs").clientHeight + 13);
    window.addEventListener("scroll", () => {
      let sct = window.pageYOffset;

      if (sct > tabOffsetTop) {
        document.querySelector(".sub-tabs").classList.add("tab-fixed");
        document.querySelector(".sub-content").style.marginTop = "70px";
      } else {
        document.querySelector(".sub-tabs").classList.remove("tab-fixed");
        document.querySelector(".sub-content").style.marginTop = "0";
      }
    });
  }

  if (bodyClass === "report") {
    const subTabItem = document.querySelectorAll(".sub-tabs__item");
    const formSubmit = document.querySelector(".report .form-submit--wide");
    const formSubmitOpen = document.querySelectorAll(".report .download");
    const formSubmitClose = document.querySelector(".report .close");
    const formUserName = document.querySelector(".report #user-name");
    const formUserPosition = document.querySelector(".report #user-position");
    const formUserMail = document.querySelector(".report #user-mail");
    const needCheck = document.querySelector(".report #sub-checkbox--personal");
    const regEmail =
      /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

    formSubmitOpen.forEach((item) => {
      item.addEventListener("click", () => {
        layerOpen("report");
      });
    });

    // formSubmit.addEventListener('click' , (event) => {
    // 	event.preventDefault();
    // // 	document.querySelectorAll('.form-field').forEach((item) => {
    // // 		item.classList.remove('warning');
    // // 	})

    // // 	// if(!needCheck.checked) {
    // // 	// 	needCheck.focus();
    // // 	// 	return false;
    // // 	// }

    // // 	// if(!formUserName.value) {
    // // 	// 	formUserName.focus();
    // // 	// 	formUserName.closest('.form-field').classList.add('warning')
    // // 	// 	return false;
    // // 	// }

    // // 	// if(!formUserPosition.value) {
    // // 	// 	formUserPosition.focus();
    // // 	// 	formUserPosition.closest('.form-field').classList.add('warning')
    // // 	// 	return false;
    // // 	// }

    // // 	// if(!formUserMail.value) {
    // // 	// 	formUserMail.focus();
    // // 	// 	formUserMail.closest('.form-field').classList.add('warning')
    // // 	// 	return false;
    // // 	// }

    // // 	// if(!regEmail.test(formUserMail.value)) {
    // // 	// 	formUserMail.focus();
    // // 	// 	formUserMail.closest('.form-field').classList.add('warning')
    // // 	// 	formUserMail.nextElementSibling.innerHTML = '질못된 입력값입니다';
    // // 	// 	return false;
    // // 	// }

    // // 	layerClose('report');
    // })

    formSubmitClose.addEventListener("click", () => {
      layerClose("report");
    });

    const tabOffsetTop =
      document.querySelector(".sub-tabs").offsetTop -
      (document.querySelector(".sub-tabs").clientHeight + 13);
    window.addEventListener("scroll", () => {
      let sct = window.pageYOffset;

      if (sct > tabOffsetTop) {
        document.querySelector(".wrap").classList.add("tab-fixed");
      } else {
        document.querySelector(".wrap").classList.remove("tab-fixed");
      }
    });
  }

  if (bodyClass === "report-detail") {
    const shareButton = document.querySelector(".sub-content__share");
    const shareClsoeButton = document.querySelector(".share-popup .close");

    if (
      document.querySelectorAll(".list-content__slide .swiper-slide").length > 1
    ) {
      const swiper = new Swiper(".list-content__slide ", {
        slidesPerView: 1.05,
        pagination: {
          el: ".swiper-pagination",
          type: "fraction",
        },
        spaceBetween: 10,
      });
    } else {
      document.querySelector(
        ".list-content__slide .list-content__image"
      ).style.marginRight = "25px";
      document.querySelector(
        ".list-content__slide .list-content__description"
      ).style.marginRight = "25px";
      document.querySelector(".list-content").style.paddingRight = "25px";
    }

    if (document.querySelectorAll(".slide-swiper .swiper-slide").length > 1) {
      const swiper = new Swiper(".slide-swiper", {
        slidesPerView: 1.05,
      });
    } else {
      document.querySelector(".slide-swiper .slide__image").style.marginRight =
        "25px";
      document.querySelector(".slide-swiper .slide__title").style.marginRight =
        "25px";
      document.querySelector(".slide-swiper .slide__download").style.right =
        "35px";
    }

    shareButton.addEventListener("click", (event) => {
      event.preventDefault();
      layerOpen("share-popup");
    });

    shareClsoeButton.addEventListener("click", (event) => {
      layerClose("share-popup");
    });

    const formSubmit = document.querySelector(
      ".report-detail .form-submit--wide"
    );
    const formSubmitOpen = document.querySelectorAll(
      ".report-detail .slide__download"
    );
    const formSubmitClose = document.querySelector(
      ".report-detail .layer__wrap--wide .close"
    );
    const formUserName = document.querySelector(".report-detail #user-name");
    const formUserPosition = document.querySelector(
      ".report-detail #user-position"
    );
    const formUserMail = document.querySelector(".report-detail #user-mail");
    const needCheck = document.querySelector(
      ".report-detail #sub-checkbox--personal"
    );
    const regEmail =
      /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;

    formSubmitOpen.forEach((item) => {
      item.addEventListener("click", (event) => {
        event.preventDefault();
        layerOpen("report");
      });
    });

    formSubmit.addEventListener("click", (event) => {
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

      if (!formUserPosition.value) {
        formUserPosition.focus();
        formUserPosition.closest(".form-field").classList.add("warning");
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

      layerClose("report");
    });

    formSubmitClose.addEventListener("click", () => {
      layerClose("report");
    });
  }

  if (bodyClass === "column") {
    const tabOffsetTop =
      document.querySelector(".sub-tabs").offsetTop -
      (document.querySelector(".sub-tabs").clientHeight + 13);
    window.addEventListener("scroll", () => {
      let sct = window.pageYOffset;

      if (sct > tabOffsetTop) {
        document.querySelector(".wrap").classList.add("tab-fixed");
      } else {
        document.querySelector(".wrap").classList.remove("tab-fixed");
      }
    });
  }

  if (bodyClass === "column-detail") {
    const shareButton = document.querySelector(".sub-content__share");
    const shareClsoeButton = document.querySelector(".share-popup .close");

    if (
      document.querySelectorAll(".list-content__slide .swiper-slide").length > 1
    ) {
      const swiper = new Swiper(".list-content__slide ", {
        slidesPerView: 1.05,
        pagination: {
          el: ".swiper-pagination",
          type: "fraction",
        },
        spaceBetween: 10,
      });
    } else {
      document.querySelector(
        ".list-content__slide .list-content__image"
      ).style.marginRight = "25px";
      document.querySelector(
        ".list-content__slide .list-content__description"
      ).style.marginRight = "25px";
      document.querySelector(".list-content").style.paddingRight = "25px";
    }

    if (document.querySelectorAll(".slide-swiper .swiper-slide").length > 1) {
      const swiper = new Swiper(".slide-swiper", {
        slidesPerView: 1.05,
      });
    } else {
      document.querySelector(".slide-swiper .slide__image").style.marginRight =
        "25px";
      document.querySelector(".slide-swiper .slide__text").style.marginRight =
        "25px";
      document.querySelector(".slide-swiper .slide__download").style.right =
        "35px";
    }

    shareButton.addEventListener("click", (event) => {
      event.preventDefault();
      layerOpen("share-popup");
    });

    shareClsoeButton.addEventListener("click", (event) => {
      layerClose("share-popup");
    });
  }

  if (bodyClass === "ir") {
    const tableTabLi = document.querySelectorAll(".table-tab ul li");
    const formSubmitOpen = document.querySelector(".sub-link");
    const formSubmitClose = document.querySelector(".layer__close");
    const formSubmitCloseCorfirm = document.querySelector(".layer__button");

    document
      .querySelector(".sub-container--announcement")
      .classList.add("content--active");

    tableTabLi.forEach((item) => {
      let data = item.childNodes[0].dataset.content;

      item.addEventListener("click", (event) => {
        tableTabLi.forEach((targets) => {
          targets.classList.remove("tab--active");
        });

        event.preventDefault();
        item.classList.add("tab--active");

        if (data === "first") {
          window.scrollTo({
            top: 200,
            behavior: "smooth",
          });
        }

        if (data === "second") {
          window.scrollTo({
            top: 1100,
            behavior: "smooth",
          });
        }

        if (data === "third") {
          window.scrollTo({
            top: 1900,
            behavior: "smooth",
          });
        }
      });
    });

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

  if (bodyClass === "ir-detail") {
    const shareButton = document.querySelector(".sub-content__share");
    const shareClsoeButton = document.querySelector(".share-popup .close");

    // if(document.querySelectorAll('.list-content__slide .swiper-slide').length > 1) {
    // 	const swiper = new Swiper('.list-content__slide ', {
    // 		slidesPerView: 1.05,
    // 		pagination: {
    // 			el: '.swiper-pagination',
    // 			type: 'fraction',
    // 		}
    // 	});
    // }else {
    // 	document.querySelector('.list-content__slide .list-content__image').style.marginRight = '25px';
    // 	document.querySelector('.list-content__slide .list-content__description').style.marginRight = '25px';
    // }

    shareButton.addEventListener("click", (event) => {
      event.preventDefault();
      layerOpen("share-popup");
    });

    shareClsoeButton.addEventListener("click", (event) => {
      layerClose("share-popup");
    });
  }

  const bodyClassList = document.body.classList;

if (bodyClassList.contains("portfolio") && !bodyClassList.contains("portfolio_detail")) {

  // if (bodyClass === "portfolio") {
    document.querySelectorAll(".list-content a").forEach((item) => {
      item.addEventListener("click", (event) => {
        event.preventDefault();
      });
    });

    document.querySelectorAll(".sub-tabs__item").forEach((item) => {
      item.addEventListener("click", (event) => {
        document.querySelectorAll(".sub-tabs__item").forEach((thisTarget) => {
          if (thisTarget.classList.contains("sub-tabs__item--active"))
            thisTarget.classList.remove("sub-tabs__item--active");
        });
        item.classList.add("sub-tabs__item--active");
      });
    });
    const subTabs = document.querySelector(".sub-tabs");
   
    const tabOffsetTop =
      document.querySelector(".sub-tabs").offsetTop -
      (document.querySelector(".sub-tabs").clientHeight + 13);

    window.addEventListener("scroll", () => {
      let sct = window.pageYOffset;

      if (sct > tabOffsetTop) {
        document.querySelector(".wrap").classList.add("tab-fixed");
      } else {
        document.querySelector(".wrap").classList.remove("tab-fixed");
      }
    });
  }

  if (bodyClass === "letter-detail") {
    const shareButton = document.querySelector(".sub-content__share");
    const shareClsoeButton = document.querySelector(".share-popup .close");

    if (document.querySelectorAll(".slide-swiper .swiper-slide").length > 1) {
      const swiper = new Swiper(".slide-swiper", {
        slidesPerView: 1.05,
      });
    } else {
      document.querySelector(".slide-swiper .slide__image").style.marginRight =
        "25px";
      document.querySelector(".slide-swiper .slide__text").style.marginRight =
        "25px";
      document.querySelector(".slide-swiper .slide__download").style.right =
        "35px";
    }

    shareButton.addEventListener("click", (event) => {
      event.preventDefault();
      layerOpen("share-popup");
    });

    shareClsoeButton.addEventListener("click", (event) => {
      layerClose("share-popup");
    });
  }

  if (bodyClass === "campaign") {
    if (
      document.querySelectorAll(".sub-head-slide__slide .swiper-slide").length >
      1
    ) {
      const swiper = new Swiper(".sub-head-slide__slide ", {
        slidesPerView: 1.15,
        spaceBetween: 10,
        slidesOffsetAfter: 60,
      });
    }
    const categoryItem = document.querySelectorAll(".accordian__item");

    categoryItem.forEach((item) => {
      item.addEventListener("click", () => {
        if (!item.classList.contains("accordian__item--active")) {
          categoryItem.forEach((targets) => {
            if (targets.classList.contains("accordian__item--active"))
              targets.classList.remove("accordian__item--active");
          });
        } else {
          item.classList.add("accordian__item--active");
        }
      });
    });
  }

  if (bodyClass === "creative") {
    if (
      document.querySelectorAll(".swiper-slide").length >
      1
    ) {
      // const swiper = new Swiper(".sub-head-slide__slide ", {
      //   slidesPerView: 1.4,
      //   spaceBetween: 20,
      //   slidesOffsetAfter: 60,
      // });

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
  }

  if (bodyClass === "about") {
    window.addEventListener("scroll", () => {
      let sct = window.pageYOffset;
      let threshold = 10;
      const visualImage = document.querySelector(".visual__image");
      const visaulBox = document.querySelector(".visual__box");

      if (sct > 10) {
        document
          .querySelector(".sub-tab-content")
          .classList.add("visual--active");
        visualImage.style.transformOrigin = `bottom left`;
        visaulBox.style.transitionDelay = `0.5s`;
      }

      if (sct == 0) {
        document
          .querySelector(".sub-tab-content")
          .classList.remove("visual--active");
        visualImage.style.transformOrigin = `top left`;
        visaulBox.style.transitionDelay = `0s`;
      }
    });

    if (document.querySelectorAll(".sub-slide .swiper-slide").length > 1) {
      const stateBar = document.querySelector(".state-bar");
      const stateBarFill = document.querySelector(".state-bar--fill");
      let stateBarWidth = stateBar.clientWidth;

      const swiper = new Swiper(".sub-slide ", {
        slidesPerView: 1.05,
        observer: true,
        observeParents: true,
        on: {
          init: function () {
            stateBarFill.style.width = (stateBarWidth / 6) * 1 + "px";
          },
        },
      });

      swiper.on("slideChange", function (e) {
        stateBarFill.style.width =
          (stateBarWidth / 6) * (swiper.realIndex + 1) + "px";
      });
    }
  }

  if (bodyClass === "personal") {
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
})();

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
  console.log("frbtm=", frbtm);

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
