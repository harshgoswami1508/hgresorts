function mainSlider() {
  var BasicSlider = $('.slider-active');
  BasicSlider.on('init', function (e, slick) {
    var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
    doAnimations($firstAnimatingElements);
  });
  BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
    var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
    doAnimations($animatingElements);
  });
  BasicSlider.slick({
    autoplay: true,
    autoplaySpeed: 3,
    dots: true,
    fade: true,
    arrows: false,
    prevArrow: '<button type="button" class="slick-prev"><img src="img/hero_thumb/arrow-left.png" alt=""><img class="secondary-img" src="img/hero_thumb/left-white.png" alt=""></button>',
    nextArrow: '<button type="button" class="slick-next"><img src="img/hero_thumb/arrow-right.png" alt=""><img class="secondary-img" src="img/hero_thumb/right-white.png" alt=""></button>',
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false
      }
    }
    ]
  })
}



const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.room-card').forEach(card => observer.observe(card));



// Mobile Menu Toggle
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
mobileMenuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

// Dark Mode Toggle
const themeToggle = document.getElementById('theme-toggle');
const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
function toggleTheme() {
  document.documentElement.classList.toggle('dark');
}
themeToggle.addEventListener('click', toggleTheme);
if (mobileThemeToggle) mobileThemeToggle.addEventListener('click', toggleTheme);

// Navigation Function
function navigate(event, sectionId) {
  event.preventDefault();
  document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
  if (!mobileMenu.classList.contains('hidden')) mobileMenu.classList.add('hidden');
}


        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' }
                        }
                    },
                    animation: {
                        fadeInUp: 'fadeInUp 0.6s ease-out forwards'
                    }
                }
            }
        }