(function ($) {
    ("use strict");

    $('.all-catergories-btn a').click(function(e) {
        e.preventDefault();

        var target = $(this).data('target');
        var container = $('.all-categories-card');

        // Remove active from all buttons and add to clicked
        $('.all-catergories-btn a').removeClass('active');
        $(this).addClass('active');

        if (target) {
            // Scroll to the category item smoothly inside the container
            var targetElement = $('#' + target);
            if (targetElement.length) {
                var containerTop = container.offset().top;
                var elementTop = targetElement.offset().top;
                var scrollTop = container.scrollTop() + (elementTop - containerTop);

                container.animate({ scrollTop: scrollTop }, 500);  // 500ms smooth scroll
            }
        } else {
            // If "All" or no target, scroll to top smoothly
            container.animate({ scrollTop: 0 }, 500);
        }
    });


    $(document).ready(function() {
        // Initialize all counters
        $('.item-value').each(function() {
            const $input = $(this);
            const target = $input.attr('id');
            const value = parseInt($input.val()) || 0;
            updateDecreaseButton(target, value);
        });

        // Use event delegation to prevent duplicate binding
        $(document).off('click', '.item-btn.plus').on('click', '.item-btn.plus', function() {
            const targetId = $(this).data('target');
            const $input = $('#' + targetId);
            let value = parseInt($input.val()) || 0;

            value += 1;
            $input.val(value);
            updateDecreaseButton(targetId, value);
            updateTotalPrice(); // Add this to update the total
        });

        $(document).off('click', '.item-btn.minus').on('click', '.item-btn.minus', function() {
            const targetId = $(this).data('target');
            const $input = $('#' + targetId);
            let value = parseInt($input.val()) || 0;

            if (value > 0) {
                value -= 1;
                $input.val(value);
                updateDecreaseButton(targetId, value);
                updateTotalPrice(); // Add this to update the total
            }
        });

        function updateDecreaseButton(targetId, value) {
            const $btn = $(`.item-btn.minus[data-target="${targetId}"]`);
            $btn.prop('disabled', value <= 0);
        }

        // Add this function to calculate total price
        function updateTotalPrice() {
            let total = 0;
            $('.item-value').each(function() {
                const $input = $(this);
                const quantity = parseInt($input.val()) || 0;
                if (quantity > 0) {
                    const priceText = $input.closest('.bag-item').find('.item-price').text();
                    const price = parseFloat(priceText.replace('$', '').trim());
                    total += quantity * price;
                }
            });
            $('#total_price').text('$' + total.toFixed(2));
        }

        // Initialize total price on load
        updateTotalPrice();
    });



    // Format card number with spaces (XXXX XXXX XXXX XXXX)
    $("#card_number").on("input", function () {
        let value = $(this).val().replace(/\D/g, "").substring(0, 16); // Remove non-digits, max 16
        let formatted = value.match(/.{1,4}/g); // Split every 4 digits
        $(this).val(formatted ? formatted.join(" ") : "");
    });

    // Restrict security code to max 5 digits
    $("#security_code").on("input", function () {
        this.value = this.value.replace(/\D/g, "").substring(0, 5);
    });

    $("#exp_date").on("input", function () {
        let input = $(this).val().replace(/\D/g, "").substring(0, 4); // Only digits, max 4
        let formatted = "";

        // Handle formatting
        if (input.length >= 2) {
            let month = parseInt(input.substring(0, 2), 10);
            month = Math.max(1, Math.min(month, 12)).toString().padStart(2, '0');

            let year = input.substring(2);

            // Format as MM/YY
            formatted = month + (year ? "/" + year : "");
        } else {
            formatted = input;
        }

        $(this).val(formatted);
    });
    // counter buttons......
    function updateButtons(targetId) {
        const value = parseInt($(`#${targetId}`).val());
        const $decBtn = $(`.decrease[data-target="${targetId}"]`);
        const $incBtn = $(`.increase[data-target="${targetId}"]`);

        $decBtn.prop("disabled", value <= 0);
        $incBtn.prop("disabled", value >= 5);
    }
    $(document).ready(function () {
        // Initialize button states
        ["small", "regular", "large", "overSized"].forEach(updateButtons);

        $(".increase").on("click", function () {
            const targetId = $(this).data("target");
            const $input = $(`#${targetId}`);
            let value = parseInt($input.val());
            if (value < 5) {
                $input.val(++value);
                updateButtons(targetId);
            }
        });

        $(".decrease").on("click", function () {
            const targetId = $(this).data("target");
            const $input = $(`#${targetId}`); // Fixed selector here
            let value = parseInt($input.val());
            if (value > 0) {
                $input.val(--value);
                updateButtons(targetId);
            }
        });
    });

    // for pickup instructions
    $("#pickupChecks").change(function () {
        $("#pickupInstructionsWrapper").toggleClass("show", this.checked);
    });
    // click card to select radio button
    $(".selectable-card").click(function () {
        $(this)
            .find('input[type="radio"]')
            .prop("checked", true)
            .trigger("change");
    });

    $('input[type="radio"]').change(function () {
        $(".selectable-card").removeClass("selected");
        if ($(this).is(":checked")) {
            $(this).closest(".selectable-card").addClass("selected");
        }
    });

    // form step js
    let currentStep = 1;

    function showStep(step) {
        $(".step").removeClass("active");
        $('.step[data-step="' + step + '"]').addClass("active");
        $(".step-circle").removeClass("active");
        $('.step-circle[data-step="' + step + '"]').addClass("active");
    }

    $(".next-btn").click(function () {
        const current = $('.step[data-step="' + currentStep + '"]');
        const inputs = current.find("input, textarea, select");
        let valid = true;

        inputs.each(function () {
            if (!this.checkValidity()) {
                this.reportValidity();
                valid = false;
                return false;
            }
        });

        if (valid && currentStep === 4) {
            const smallVal = parseInt($("#small").val());
            const regularVal = parseInt($("#regular").val());
            const largeVal = parseInt($("#large").val());
            const overSizedVal = parseInt($("#overSized").val());

            if (
                smallVal === 0 &&
                regularVal === 0 &&
                largeVal === 0 &&
                overSizedVal === 0
            ) {
                alert(
                    "Please select at least one bag count (Small, Regular, Large, or OverSized)."
                );
                valid = false;
            }
        }

        if (valid && currentStep < 6) {
            currentStep++;
            showStep(currentStep);
        }
    });

    $(".prev-btn").click(function () {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);

    // password show/hide
    $(".eye-icon").on("click", function () {
        const $icon = $(this).find("i");
        const $passwordInput = $("#exampleInputPassword1");

        if ($passwordInput.attr("type") === "password") {
            $passwordInput.attr("type", "text");
            $icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            $passwordInput.attr("type", "password");
            $icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    // form submit redirect to index2.html
    $("#signup-form").on("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission
        setTimeout(function () {
            alert("Form submitted successfully!");
        }, 1000); // Show alert after 1 second
        window.location.href = "index2.html"; // Redirect to index2.html
    });
    // banner slider js
    $(".banner-slider").slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        arrows: false,
        autoplay: true,
    });

    $(".services-slider-two").slick({
        dots: true,
        infinite: true,
        speed: 800,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        autoplay: true,

        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });

    // dynamic year for copyright
    document.getElementById("copyright_year").textContent =
        new Date().getFullYear();

    // data background image js
    $("[data-background]").each(function () {
        $(this).css(
            "background-image",
            "url(" + $(this).attr("data-background") + ")"
        );
    });

    // fixed menu js
    $(window).on("scroll", function () {
        let scroll = $(window).scrollTop();
        if (scroll < 120) {
            $("#sticky-header").removeClass("sticky-menu");
            $("#header-fixed-height").removeClass("active-height");
        } else {
            $("#sticky-header").addClass("sticky-menu");
            $("#header-fixed-height").addClass("active-height");
        }
    });

    // Magnific popup image js
    $(".image-popup").magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });

    // Mobile menu js start
    $(".mobile-topbar .bars").on("click", function () {
        $(".mobile-menu-overlay,.mobile-menu-main").addClass("active");
    });

    $(".close-mobile-menu,.mobile-menu-overlay").on("click", function () {
        $(".mobile-menu-overlay,.mobile-menu-main").removeClass("active");
    });

    $(".sub-mobile-menu ul").hide();
    $(".sub-mobile-menu a").on("click", function () {
        $(".sub-mobile-menu ul").not($(this).next("ul")).slideUp(300);
        $(".sub-mobile-menu a i")
            .not($(this).find("i"))
            .removeClass("fa-chevron-up")
            .addClass("fa-chevron-down");
        $(this).next("ul").slideToggle(300);
        $(this).find("i").toggleClass("fa-chevron-up fa-chevron-down");
    });

    /* Odometer Activeate js */
    $(document).ready(function () {
        $(".odometer").appear(function () {
            var odo = $(".odometer");
            odo.each(function () {
                var countNumber = $(this).attr("data-count");
                $(this).html(countNumber);
            });
        });
    });

    // Banner slider js
    $(".banner-slider").slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        autoplay: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Image reveal js
        let revealContainers = document.querySelectorAll(".reveal");
        revealContainers.forEach((container) => {
            let image = container.querySelector("img");
            let tl = gsap.timeline({
                scrollTrigger: {
                    trigger: container,
                    toggleActions: "play none none none",
                },
            });

            tl.set(container, {
                autoAlpha: 1,
            });

            if (container.classList.contains("zoom-out")) {
                // Zoom-out effect
                tl.from(image, 1.5, {
                    scale: 1.4,
                    ease: Power2.out,
                });
            } else if (
                container.classList.contains("left") ||
                container.classList.contains("right")
            ) {
                let xPercent = container.classList.contains("left")
                    ? -100
                    : 100;
                tl.from(container, 1.5, {
                    xPercent,
                    ease: Power2.out,
                });
                tl.from(image, 1.5, {
                    xPercent: -xPercent,
                    scale: 1,
                    delay: -1.5,
                    ease: Power2.out,
                });
            }
        });

        // Split text animation
        if ($(".split-text").length > 0) {
            let st = $(".split-text");
            if (st.length == 0) return;
            gsap.registerPlugin(SplitText);
            st.each(function (index, el) {
                el.split = new SplitText(el, {
                    type: "lines,words,chars",
                    linesClass: "tp-split-line",
                });
                gsap.set(el, {
                    perspective: 800,
                });
                if ($(el).hasClass("right")) {
                    gsap.set(el.split.chars, {
                        opacity: 0,
                        x: "50",
                        ease: "Back.easeOut",
                    });
                }
                if ($(el).hasClass("left")) {
                    gsap.set(el.split.chars, {
                        opacity: 0,
                        x: "-50",
                        ease: "circ.out",
                    });
                }
                if ($(el).hasClass("up")) {
                    gsap.set(el.split.chars, {
                        opacity: 0,
                        y: "80",
                        ease: "circ.out",
                    });
                }
                if ($(el).hasClass("down")) {
                    gsap.set(el.split.chars, {
                        opacity: 0,
                        y: "-80",
                        ease: "circ.out",
                    });
                }
                el.anim = gsap.to(el.split.chars, {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%",
                    },
                    x: "0",
                    y: "0",
                    rotateX: "0",
                    scale: 1,
                    opacity: 1,
                    duration: 0.8,
                    stagger: 0.02,
                });
            });
        }
    });
})(jQuery);
