<?php
header("Content-Type:text/css");
//$color = "#f0f"; // Change your Color Here
$color = "#003366"; // Change your Color Here
$secondColor = "#FFCC99"; // Change your Color Here

function checkhexcolor($color){
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) AND $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color OR !checkhexcolor($color)) {
    $color = "#336699";
}


function checkhexcolor2($secondColor){
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

if (isset($_GET['secondColor']) AND $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor OR !checkhexcolor2($secondColor)) {
    $secondColor = "#336699";
}
?>

.header-top-item a i {
color: <?php echo $color ?>;
}
.banner__content .banner__category {
color: <?php echo $color ?>;
}

.cmn--btn {
border: 1px solid <?php echo $color ?>33;
background: <?php echo $color ?>;
}

.loader {
color: <?php echo $color ?>;
}

.owl-dots .owl-dot.active {
background: <?php echo $color ?>;
}
.owl-dots .owl-dot {
border: 1px solid <?php echo $color ?>;
}

.book__wrapper .book__title::after {
background: <?php echo $color ?>;
}
.form--label i {
color: <?php echo $color ?>;
}

button.cmn--btn:hover {
background: <?php echo $color ?>;
}
.cmn--btn:hover, .cmn--btn:focus, .select2-selection select2-selection--single {
border-color: <?php echo $color ?>99;
}
.btn--base, .badge--base, .bg--base, .custom--accordion .accordion-button[aria-expanded="true"]  {
background-color: <?php echo $color ?> !important;
}

.section__header .section__category::before, .section__header .section__category::after {
background: <?php echo $color ?>;
}

.section__header {
border-bottom: 1px solid <?php echo $color ?>;66;
}

.car__rental-content-body .specification li i {
color: <?php echo $color ?>;
}

.shape {
-webkit-text-stroke: 1px <?php echo $color ?>;
}

.scrollToTop {
background: <?php echo $color ?>;
}
.choose__item-icon {
border: 1px solid <?php echo $color ?>33;
}

.custom-tab-menu .tab-menu li.active .tab-menu-icon span {
background: <?php echo $color ?>;
}
.custom-tab-menu .tab-menu li .tab-menu-icon {
border: 1px solid <?php echo $color ?>66;
}
.custom-tab-menu::after {
border: 2px dashed <?php echo $color ?>;
}

.plan__item .plan__header .plan__header-price .price {
background: <?php echo $color ?>;
}
.plan__item .plan__header {
background: <?php echo $color ?>33;
}

.faq__item.open .faq__title {
background: <?php echo $color ?>;
}
.faq__item .faq__title .right__icon::before, .faq__item .faq__title .right__icon::after {
background: <?php echo $color ?>;
}

.text--base, .menu li.active a  {
color: <?php echo $color ?> !important;
}

.section__header .section__title .text--base {
-webkit-text-stroke-color: <?php echo $color ?>;
}

button.cmn--btn {
background: <?php echo $color ?>;
}

.footer-wrapper .footer__widget .widget__title::before, .footer-wrapper .footer__widget .widget__title::after {
background: <?php echo $color ?>66;
}

.widget__links li a:hover {
color: <?php echo $color ?>;
}
.widget__links li a::before {
background: <?php echo $color ?>33;
}

.form--control:focus, .form-control:disabled:focus, .form-control[readonly]:focus {
border-color: <?php echo $color ?>66;
}

.footer__contact li .icon {
color: <?php echo $color ?>b3;
}

.cmn--btn.active:hover, .cmn--btn.active:focus {
background: <?php echo $color ?>;
}

.post__item .post__content .meta__date {
border-left: 5px solid <?php echo $color ?>;
}

.post__item .post__content .meta__date .meta__item i {
color: <?php echo $color ?>;
}
.post__item .post__read {
color: <?php echo $color ?>;
}
h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {
color: <?php echo $color ?>;
}
.widget__post .widget__post__content span {
color: <?php echo $color ?>;
}

.account__wrapper {
border: 1px dashed <?php echo $color ?>4d;
}

.contact__item .contact__icon {
color: <?php echo $color ?>;
border: 1px dashed <?php echo $color ?>4d;
}
.contact__item {
border: 1px dashed <?php echo $color ?>4d;
}
.contact__item:hover .contact__icon {
background: <?php echo $color ?>;
}
.forgot-pass, .contact__item .contact__body a {
color: <?php echo $color ?>;
}

.newsletter-wrapper .footer-logo {
border-right: 1px solid <?php echo $color ?>;
}

.footer__contact li:not(:last-child) {
border-bottom: 1px dashed <?php echo $color ?>4d;
}

.breadcrumb li {
color: <?php echo $color ?>;
}

.widget {
border: 1px dashed <?php echo $color ?>4d;
}
.widget .title::before, .widget .title::after {
border-bottom: 2px dashed <?php echo $color ?>33;
}
.widget .category-link li a {
border-bottom: 1px dashed <?php echo $color ?>33;
}

.rent__item {
box-shadow: 0 0 15px <?php echo $color ?>1a;
border: 1px dashed <?php echo $color ?>4d;
}

.border--dashed {
border: 1px dashed <?php echo $color ?>4d;
}

.price-area .item {
color: <?php echo $color ?>;
}
.rent__item .rent__content .car-info i{
color: <?php echo $color ?>;
}

.nav--tabs .nav-item .nav-link.active {
background: <?php echo $color ?>;
}
.single__details-content .specification-item i , .auth-devide span{
color: <?php echo $color ?>;
}

.single__details-content {
border: 1px dashed <?php echo $color ?>4d;
}

.dashboard__item .dashboard__thumb {
background: <?php echo $color ?>;
}
.cmn--table thead tr th{
background: <?php echo $color ?>;
}

.btn--primary, .badge--primary, .bg--primary, .menu li .submenu li.active > a , .dropdown-list>.dropdown-list__item:hover{
background-color: <?php echo $color ?> !important;
}

*::selection, .payment-system-list.is-scrollable::-webkit-scrollbar-thumb {
    background-color:  <?php echo $color ?> !important;
}


@media (min-width: 992px)
.menu li .submenu li:hover > a {
background: <?php echo $color ?>;
}

.menu li .submenu li:hover > a {
background: <?php echo $color ?>;
}

.message__chatbox {
border: 1px dashed <?php echo $color ?>4d;
}

.custom--card {
border: 1px dashed <?php echo $color ?>4d;
}

.pagination .page-item.active span, .pagination .page-item.active a, .pagination .page-item:hover span, .pagination .page-item:hover a {
    background: <?php echo $color ?>;
}
.payment-item:has(.payment-item__radio:checked) .payment-item__check {
  border: 3px solid <?php echo $color ?>;
}
.payment-item__check{
    border: 1px solid <?php echo $color ?>;

}

.select2-container--open .select2-selection.select2-selection--single,
.select2-container--open .select2-selection.select2-selection--multiple, .select2-container--default .select2-search--dropdown .select2-search__field:focus {
  border-color: <?php echo $color ?> !important;
}

.payment-item:has(.payment-item__radio:checked) {
    border-left: 3px solid <?php echo $color ?> ;
}
