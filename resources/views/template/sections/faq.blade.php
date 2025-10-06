@php
    $faqContent = getContent('faq.content', true);
    $faqElements = getContent('faq.element', false, null, true);
@endphp


<section class="faq-section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$faqContent->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$faqContent->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$faqContent->data_values->subheading) }}</span>
            <h2 class="section__title">{{ __(@$faqContent->data_values->heading) }}</h2>
        </div>

        <div class="row gy-4 justify-content-center">
            <div class="accordion custom--accordion" id="faqAccordion1">
                @foreach ($faqElements as $item)
                    <div class="accordion-item">
                        <h6 class="accordion-header collapsed" id="heading{{ $item->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $item->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $item->id }}">
                                {{ __($item->data_values->question) }}
                            </button>
                        </h6>
                        <div id="collapse{{ $item->id }}" class="accordion-collapse collapse"
                             aria-labelledby="heading{{ $item->id }}" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                <p>{{ __($item->data_values->answer) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>



@push('script')
    <script>
        "user strict";

        $(".faq-single__header").each(function() {
            $(this).on("click", function() {
                $(this).siblings(".faq-single__content").slideToggle();
                $(this).parent(".faq-single").toggleClass("active");
            });
        });


        window.addEventListener("DOMContentLoaded", () => {
            let faqElements = document.querySelectorAll(".accordion-item");
            let faqContainer = document.getElementById("faqAccordion1");
            let oddElement = "";
            let evenElement = "";

            if (
                faqContainer == undefined ||
                faqContainer.tagName != "DIV" ||
                typeof faqElements != "object"
            )
                return false;

            Array.from(faqElements).forEach(function(element, i) {
                if (i % 2 == 0) {
                    evenElement += element.outerHTML;
                } else {
                    oddElement += element.outerHTML;
                }
            });

            faqContainer.innerHTML = `
                <div class="row gy-3">
                <div class="col-lg-6">${evenElement}</div>
                <div class="col-lg-6">${oddElement}</div>
                </div>`;
        });
    </script>
@endpush
