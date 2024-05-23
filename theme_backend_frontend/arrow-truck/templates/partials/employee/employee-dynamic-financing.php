<div class="afiv-extras container grid md:grid-cols-6 lg:grid-cols-12 gap-4 md:gap-8">
    <div class="afiv-extras-wrapper col-span-12">
        <div class="afiv-financing-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'financing_headline' ]; ?>

            <svg id="financing-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="financing-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-financing-content afiv-content hidden">

            <?php echo $content[ 'financing_copy' ]; ?>

        </div>
    </div>
    <div class="afiv-extras-wrapper col-span-12">
        <div class="afiv-warranties-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'warranties_headline' ]; ?>

            <svg id="warranties-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="warranties-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-warranties-content afiv-content hidden">

            <?php echo $content[ 'warranties_copy' ]; ?>

        </div>
    </div>
    <div class="afiv-extras-wrapper col-span-12">
        <div class="afiv-insurance-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'insurance_and_protection_headline' ]; ?>

            <svg id="insurance-and-protection-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="insurance-and-protection-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-insurance-content afiv-content hidden">

            <?php echo $content[ 'insurance_and_protection_copy' ]; ?>

        </div>
    </div>
    <div class="afiv-extras-wrapper col-span-12">
        <div class="afiv-emergency-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'emergency_services_headline' ]; ?>

            <svg id="emergency-services-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="emergency-services-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-emergency-content afiv-content hidden">

            <?php echo $content[ 'emergency_services_copy' ]; ?>

        </div>
    </div>
</div>
<div class="afiv-welcome container grid md:grid-cols-6 lg:grid-cols-12 gap-4 md:gap-8">
    <h2 class="col-span-12 afiv-welcome-h1">

        <?php echo $content[ 'welcome_headline' ]; ?>

    </h2>

    <?php echo $content[ 'welcome_copy' ]; ?>

    <div class="afiv-extras-wrapper col-span-12">
        <div class="afiv-phonenumbers-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'helpful_numbers_headline' ]; ?>

            <svg id="helpful-phone-numbers-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="helpful-phone-numbers-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-phonenumbers-content afiv-content hidden">

            <?php echo $content[ 'helpful_numbers_copy' ]; ?>

        </div>
    </div>
    <div class="afiv-extras-wrapper col-span-12">
        <div class="afiv-loanportals-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'transport_funding_headline' ]; ?>

            <svg id="transport-funding-loan-portals-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="transport-funding-loan-portals-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-loanportals-content afiv-content hidden w-full">

            <?php echo $content[ 'transport_funding_copy' ]; ?>

        </div>
    </div>
    <div class="afiv-extras-wrapper afiv-last-wrapper col-span-12">
        <div class="afiv-financing-header afiv-header flex justify-between items-center">

            <?php echo $content[ 'customer_appreciation_headline' ]; ?>

            <svg id="customer-appreciation-arrow-down" class="afiv-arrow-down icon icon-chevron-down inline-block">
                <use xlink:href="#icon-chevron-down"></use>
            </svg>
            <svg id="customer-appreciation-arrow-up" class="afiv-arrow-up icon icon-chevron-up hidden">
                <use xlink:href="#icon-chevron-up"></use>
            </svg>
        </div>
        <div class="afiv-financing-content afiv-content hidden">

            <?php echo $content[ 'customer_appreciation_copy' ]; ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    $('.afiv-header').click(function() {
        $(this).find('.afiv-arrow-down').toggleClass('hidden').toggleClass('inline-block');
        $(this).find('.afiv-arrow-up').toggleClass('hidden').toggleClass('inline-block');
        $(this).parent().find('.afiv-content').toggleClass('hidden');
    });
</script>
