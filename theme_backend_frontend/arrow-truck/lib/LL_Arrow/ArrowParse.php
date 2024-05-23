<?php

    class ArrowParse
    {
        public $inventory;
        public $location;
        public $employee;
        public $location_default_image;
        public $employee_default_image;
        public $employee_image_path;
        public $refresh_frequency;
        public $enable_lat_long = false;

        function __construct(  )
        {
            $this->inventory                = null;
            $this->location                 = null;
            $this->employee                 = null;
            $this->enable_lat_long          = true;
            $this->location_default_image   = get_template_directory_uri() . "/assets/img/default_LOCATION.jpg";
            $this->employee_image_path      = "https://www.arrowtruckhost.com/images/slsphotos";
            $this->refresh_frequency        = ARROW_DATA_REFRESH_FREQUENCY_IN_HOURS;
        }

        public static function order_reps_by_title( $reps )
        {
            if ( ! $reps ) {
                return null;
            }

            $ordered_reps = [];

            foreach( $reps as $key => $rep ) {

                if ( ! $rep ) {
                     continue;
                }

                switch ( $rep->title ) {
                    case "Branch Manager":
                        $order = 9;
                        break;
                    case "Finance and Insurance Manager":
                        $order = 8;
                        break;
                    case "Assistant Branch Manager":
                        $order = 7;
                        break;
                    case "Sales and Purchasing Manager":
                        $order = 6;
                        break;
                    case "Lead Sales Associate":
                        $order = 5;
                        break;
                    case "Retail Sales Consultant":
                        $order = 4;
                        break;
                    case "Administrative Assistant":
                        $order = 3;
                        break;
                    case "Inventory Coordinator":
                        $order = 2;
                        break;
                    default:
                        $order = 1;
                        break;
                }

                $ordered_reps[ $rep->ID ] = $rep;
                $ordered_reps[ $rep->ID ]->order = $order;
            }

            array_multisort( array_column( $ordered_reps, 'order'), SORT_DESC, $ordered_reps );

            return $ordered_reps;
        }

        public function location_hours( $branch_id )
        {
            if ( ! $branch_id ) {
                return null;
            }

            switch ( strtoupper( $branch_id ) ) {
                case "AT": // Atlanta, Georgia
                    $weekdays   = "Monday thru Friday: 8 AM to 6 PM";
                    $saturdays  = "Saturday: 9 AM to 1 PM";
                    $sunday     = null;
                    break;
                case "FT": // Fontana, California
                    $weekdays   = "Monday thru Friday: 8 AM to 5 PM";
                    $saturdays  = "Saturday: 8 AM to 1 PM";
                    $sunday     = null;
                    break;
                case "JX": // Jacksonville, Florida
                    $weekdays   = "Monday thru Friday: 8 AM to 5 PM";
                    $saturdays  = "Saturday: 9 AM to 1 PM";
                    $sunday     = null;
                    break;
                case "NJ": // Newark, New Jersey
                    $weekdays   = "Monday thru Friday: 8 AM to 6 PM";
                    $saturdays  = "Saturday: 9 AM to 2 PM";
                    $sunday     = null;
                    break;
                case "PX": // Phoenix, Arizona
                    $weekdays   = "Monday thru Friday: 8 AM to 5 PM";
                    $saturdays  = "Saturday: 9 AM to 1 PM";
                    $sunday     = null;
                    break;
                case "SL": // St. Louis, Missouri
                    $weekdays   = "Monday thru Friday: 8 AM to 6 PM";
                    $saturdays  = "Saturday: 8 AM to 12 PM";
                    $sunday     = null;
                    break;
                case "SP": // Springfield, Missouri
                    $weekdays   = "Monday thru Friday: 8 AM to 5 PM";
                    $saturdays  = "Saturday: 8 AM to 12 PM";
                    $sunday     = null;
                    break;
                default:
                    $weekdays   = "Monday thru Friday: 8 AM to 6 PM";
                    $saturdays  = "Saturday: 8 AM to 1 PM";
                    $sunday     = null;
                    break;
            }

            return ( object ) [
                'weekdays'  => $weekdays,
                'saturday'  => $saturdays,
                'sunday'    => $sunday
            ];

        }

        public static function location_about( $branch_id )
        {
            if ( ! $branch_id ) {
                return null;
            }

            switch ( strtoupper( $branch_id ) ) {
                case "AT": // Atlanta, Georgia
                    $title     = "<h1 class=\"hdg-3\">Atlanta Branch</h1>";
                    $about_1   = "<p>Arrow Truck Sales is committed to helping Atlanta truck drivers and owner-operators get the quality semi-trucks they need at a price they can afford. We offer flexible financing options—making it easier to qualify than anyone else in the industry—and provide insurance, warranties, and other protections to help ensure your investment is covered. Our Atlanta branch is conveniently located in nearby Conley and serves truckers throughout the area. Call or stop by today to learn more about our inventory, financing, insurance, and more.</p>";
                    $about_2   = "<p>The team at our Atlanta branch of Arrow Truck Sales is experienced, knowledgeable, and committed to the success of your trucking business. Whether you’re looking for a single truck or a fleet of trucks, searching for a specific make, or exploring all your options, our sales team can help you find the exact truck or trucks you need to help build your business. We also have an onsite finance and insurance manager to help you purchase additional insurance or warranties for your business and guide you through applying for financing with Arrow Truck Sales. Need financing or insurance? Reach out to us today to speak to one of our representatives. Our multilingual team will be proud to serve you.</p>";
                    $about_3   = "<p>Looking for affordable, reliable semi-trucks for sale in Atlanta, GA? Arrow Truck Sales is a leading retailer of various semi-truck makes and models. We have locations all around the country, including right here in Conley, conveniently located close to the bustling city of Atlanta. On our lot, you’ll find trucks from major semi-truck manufacturers like Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more. Our used semi-trucks are carefully inspected for safety quality to help ensure that you get a reliable truck that will last you for years—all for a much more affordable price than you can find elsewhere. Check out our existing inventory at our Atlanta location. Don’t see what you want? Speak to a representative to get help with finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Truck You Need Today</h2>
                    <p>At Arrow Truck Sales, we want to get you into a new semi-truck faster, with fewer headaches for you. That’s why we offer easy, flexible financing options on all of our vehicles and incentives on many of our in-stock trucks. If you’re looking to buy a semi-truck—or an entire fleet of trucks—contact the team at Arrow Truck Sales today, and we’ll help you find the truck you’re looking for. Be sure to check out our online financing tools, so you can get pre-qualified and know how much of a down payment you’ll need for the truck you want. Give us a call, or stop by our Atlanta location, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "CH": // Chicago, Illinois
                    $title     = "<h1 class=\"hdg-3\">Chicago Branch</h1>";
                    $about_1   = "<p>Arrow Truck Sales is committed to helping truck drivers and owner-operators in the Chicago area get the quality semi-trucks they need at a price they can afford. We offer flexible financing options and provide insurance, warranties, and other protections to help ensure that your investment is covered—all while making it faster and easier to get into a semi-truck. Our Chicago branch is conveniently located in nearby Bolingbrook, IL, and serves truckers throughout the greater Chicago area. Call or stop by today to learn more about our inventory of used semi-trucks, financing options, insurance, and more.</p>";
                    $about_2   = "<p>The team at our Chicago branch of Arrow Truck Sales is experienced, knowledgeable, and committed to the success of your trucking business. Are you searching for a specific make or still exploring all your options? Are you looking to purchase a single truck or a fleet of trucks? Whatever you’re in the market for, our sales team can help you find the truck or trucks you need to help build your business. Need financing or insurance? We also have an onsite finance and insurance manager to help you purchase insurance or warranties for your semi-trucks and guide you through applying for financing with Arrow Truck Sales. Reach out to us today to speak to one of our representatives. Our multilingual team will be proud to serve you in English, Spanish, Russian, Ukrainian, Serbian, or Polish.</p>";
                    $about_3   = "<p>Looking for affordable, reliable used semi-trucks in Chicago, IL? Arrow Truck Sales is a leading retailer of all major semi-truck makes and models in the United States. We have locations across the country, including close to the bustling city of Chicago, in nearby Bollingbrook. On our lot, you’ll find trucks from major semi-truck manufacturers like Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more. Our used semi-trucks are carefully inspected for safety quality to help ensure that you get a reliable truck that will last you for years—all for a much more affordable price than you can find elsewhere. Check out our existing inventory at our Chicago location. Don’t see what you want? Speak to a representative to get help with finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Semi-Truck You Need</h2>
                    <p>At Arrow Truck Sales, we want to get you into a new semi-truck faster, with fewer headaches for you. That’s why we offer easy, flexible financing options on all of our vehicles and incentives on many of our in-stock trucks. If you’re looking to buy a semi-truck—or an entire fleet of trucks—contact the team at Arrow Truck Sales today, and we’ll help you find the truck you’re looking for. Be sure to check out our online financing tools, so you can get pre-qualified and have an idea of how much of a down payment you’ll need for the truck you want. Give us a call, or stop by our Chicago location, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "CN": // Cincinnati, Ohio
                    $title     = "<h1 class=\"hdg-3\">Cincinnati Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we consider it our mission to help those in the trucking industry get the quality semi-trucks they need at an affordable price. Whether you’re buying your first truck or already own a fleet of commercial vehicles, we offer flexible in-house financing that helps you get into a truck faster and more easily than with other lenders. We also provide additional insurance and warranty options to help you protect your investment. Our Cincinnati branch is located right off I275, providing easy access to our lot for truckers and owner-operators throughout the area. Stop by our lot today or look below to see our current semi-truck inventory. If you need help with financing and insurance options, give us a call or visit us today to meet our helpful staff and have all your questions answered.</p>";
                    $about_2   = "<p>Our team members here at Arrow Truck Sales allow us to provide our customers with not only high-quality, affordable used semi-trucks, but with exceptional customer service as well. Our team is knowledgeable, experienced, and committed to your success in the trucking industry. We do more than try to sell you a truck, we help guide you through the selection process to ensure you find the right semi-truck to meet your needs and bolster your company’s growth. If needed, we can also help you with in-house financing and add-on warranties and insurance to provide the additional protection you need for your new truck. Our onsite finance and insurance manager is available to help you apply for financing with Arrow Truck Sales, and can guide you through purchasing any additional warranties.</p>
                    <p>Whether you’re buying your first truck or upgrading your entire fleet, the team at Arrow Truck Sales is here for you. Reach out to us today for friendly, expert service from our exceptional team members.</p>";
                    $about_3   = "<p>If you’re looking for a used semi-truck for sale in Cincinnati, make Arrow Truck Sales your first stop. As a leading retailer of all major brands of used semi-trucks, we carry high-quality used semis from the manufacturers that you trust most, including International, Freightliner, Peterbilt, Volvo, Mack, and more. Every semi on our lot is carefully inspected before we list it to ensure that it’s safe, reliable, and up to our standards of excellence. In fact, we’re so confident in the reliability of the used trucks we sell that we back them with a standard 6-month warranty to protect you and your investment.</p>
                    <p>Check out the listings below to see some of our existing inventory at our Cincinnati, OH, location, or stop by our lot off I-275 today. Can’t find a truck that meets your current needs? We encourage you to contact our team to get help finding the right semi-truck for you.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Get into a Semi-Truck Sooner</h2>
                    <p>At Arrow Truck Sales, we want to help you find the right semi-truck at the right price, and get you into that truck more quickly and easily. That’s why we offer a large selection of quality semis from a variety of manufacturers, as well as flexible in-house financing options that make it easier to qualify than when working with other lenders. In addition to our standard 6-month warranty, we also offer optional extended warranties to help you protect your investment.</p>
                    <p>Whether you’re looking for a Mack or a Peterbilt, for a single truck or an entire fleet of them, contact Arrow Truck Sales today, and get the help you need in finding the right semi-truck to support your success. Be sure to check out our online tools to get pre-qualified for our in-house financing and even receive an estimate on the size of your down payment. Then, stop by our Cincinnati, OH, location to find the right truck, and let us help you get behind the wheel even sooner.</p>";
                    break;
                case "DA": // Dallas, Texas
                    $title     = "<h1 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">About our Dallas Location</h1>";
                    $about_1   = "<p class=\"mb-3\">Arrow Truck Sales is saddened to announce that effective July 31, 2023 we have discontinued all selling operations at our three Texas branches in Dallas, Houston and San Antonio.</p>
                    <p class=\"mb-3\">Arrow will continue serving a best-in-class truck buying experience at all of our other United States and Canada locations across North America. Arrow will continue to support our Texas customers on all after-sales situations through our corporate customer service department.</p>
                    <p class=\"mb-3\">Anyone living in Texas can still purchase trucks through our other locations across the country. Online shopping is also available at all locations.</p>";
                    $about_2   = "
                    <p class=\"flex flex-wrap md:justify-evenly\">
                        <a class=\"btn w-full mr-6 ml-6 md:w-auto\" href=\"/search-inventory\" data-arrow-btn=\"true\">
                            Shop Now
                        </a>
                        <br>
                        <a class=\"btn w-full mr-6 ml-6 md:w-auto\" href=\"/locations\" data-arrow-btn=\"true\">
                            All Locations
                        </a>
                        <br>
                        <a class=\"btn w-full mr-6 ml-6 md:w-auto\" href=\"/used-semi-trailers-for-sale\" data-arrow-btn=\"true\">
                            Contact Us
                        </a>
                    </p>";
                    $about_3   = "<p class=\"mb-3\">Looking for an affordable, reliable semi-truck for sale in Dallas, TX? Arrow Truck Sales is a leading retailer of a variety of semi-truck makes and models with locations all around the country. On our lots, you’ll find trucks from major semi-truck manufacturers like Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more.</p>
                    <p class=\"mb-3\">Our used semi-trucks are carefully inspected for safety quality to help ensure that you get a reliable truck that will last you for years—all for a much more affordable price than you can find elsewhere. Check out our existing inventory and if you don’t see what you want, you can speak to a representative to get help with finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Truck You Need Today</h2>
                    <p class=\"mb-3\">At Arrow Truck Sales, we want to get you into a new semi-truck faster, with fewer headaches for you. That’s why we offer easy, flexible financing options on all of our vehicles, and incentives on many of our in-stock trucks. If you’re looking to buy a semi-truck—or an entire fleet of trucks—contact the team at Arrow Truck Sales today, and we’ll help you find the truck you’re looking for. Be sure to check out our online financing tools, so you can get pre-qualified and have an idea of how much of a down payment you’ll need for the truck you want. Give us a call, or stop by one of our locations, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "FR": // Fresno, California
                    $title     = "<h1 class=\"hdg-3\">Fresno Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we’re proud to be one of the most trusted used semi dealers in the country. Truck drivers and owner-operators throughout Fresno and across the country have trusted us to provide the quality semi-trucks they need for years, and that’s a trust we don’t take lightly. We’re committed to making it faster and easier to get into a semi-truck that fits your needs, and at a price you can afford. In addition to carrying trucks from the top manufacturers in the world, we also offer flexible in-house financing, add-on insurance, extended warranties, and other protections for your investment.</p>
                    <p>Our Fresno branch is conveniently right off Highway 99. Call or stop by today to explore our inventory of used semi-trucks, or learn more about our financing options, insurance, and other services.</p>";
                    $about_2   = "<p>The Arrow Truck Sales team members at our Fresno branch have the knowledge and experience you need to find the right semi-truck for you. Whatever you’re looking for in a semi-truck, our sales associates can help you find an affordable used semi that meets those needs. Our team is committed to helping you succeed in the trucking business.</p>
                    <p>We also have an onsite finance and insurance manager that is available to help you apply for financing, purchase insurance, or add extended warranties to your semi for additional protection. Reach out to us today to speak to one of our representatives. Our multilingual team will be proud to serve you in Spanish, Punjabi, Hindi, and English.</p>";
                    $about_3   = "<p>Are you searching for a used semi-truck for sale in Fresno, CA? Arrow Truck Sales is a leading retailer of used semis from the most trusted semi-truck manufacturers, both domestic and international. We have locations across the country, including right here in Fresno, CA, and we have served truckers and owner-operators throughout the United States for years. We carry trucks from the name brands you know and trust, including Mack, International, Peterbilt, Kenworth, Freightliner, and more. Our used semi-trucks are affordably priced and thoroughly inspected to ensure safety, quality, and long-term reliability of your new truck.</p>
                    <p>Visit our Fresno lot or view our inventory below to see what we currently have in stock. Don’t see the truck you’re looking for? Contact us to get help finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Let Us Find the Right Semi for You</h2>
                    <p>At Arrow Truck Sales, it’s our goal to get you into a new semi-truck faster, with fewer headaches for you. If you’re looking for a used semi-truck, contact the team at Arrow Truck Sales today. We’ll help you find the right semi to fit your needs and help your trucking career thrive. Be sure to get pre-qualified and receive a down payment estimate using our online financing tools. Call now or stop by our Fresno location, and one of our sales representatives will be happy to help you find the semi-truck you need.</p>";
                    break;
                case "FT": // Fontana, California
                    $title     = "<h1 class=\"hdg-3\">Fontana Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, our goal is to help those in the trucking industry find the quality semi-trucks they need at a price that they can afford. Whether you’re buying your first truck or already own a fleet of commercial vehicles, we provide flexible in-house financing to help you get into a truck faster and more easily than with other lenders. We also offer additional insurance and warranty options to help you protect your business investment. Our Fontana branch is located right off I-10, and our team is committed to serving truckers and owner-operators throughout the area. Stop by our lot today or check out our current semi-truck inventory below. For questions regarding financing and insurance, give us a call or visit us today to have all your questions answered.</p>";
                    $about_2   = "<p>The team at Arrow Truck Sales is committed to providing our customers with not only high-quality, affordable used semi-trucks, but with exceptional customer service as well. Our team is knowledgeable, experienced, and committed to your success in the trucking industry. We do more than try to sell you a truck; we help guide you through the selection process to ensure you find the right semi-truck to support your needs and grow your business. If needed, we can also help you with in-house financing and add- on warranties and insurance for additional protection on your new truck. Our onsite finance and insurance manager is available to help you apply for financing with Arrow Truck Sales, and can guide you through purchasing any additional warranties you need.</p>
                    <p>Whether you’re buying your first truck or upgrading your entire fleet, the team at Arrow Truck Sales is here for you. Reach out to us today for friendly, expert service from our exceptional team members.</p>";
                    $about_3   = "<p>If you’re looking for a used semi-truck for sale in Fontana, make Arrow Truck Sales your first stop. We are a leading retailer of all major semi-truck brands. On our Fontana lot, we carry high-quality used semis from the manufacturers that you trust most, including International, Freightliner, Peterbilt, Volvo, Mack, and more. Every semi on our lot is carefully inspected to ensure that it’s safe, reliable, and up to our standards of excellence. In fact, we’re so confident in the reliability of the used semi-trucks we sell that we back them all with a 6-month warranty to protect you and your investment.</p>
                    <p>Check out the listings below to see some of our existing inventory at our Fontana, CA, location, or stop by our lot off I-10 today. If you can’t find the truck you’re looking for, we encourage you to contact our team for help finding the right semi-truck for you.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find a Quality Used Semi-Truck</h2>
                    <p>At Arrow Truck Sales, we offer a large selection of quality semis from a variety of manufacturers, as well as flexible in-house financing options that make it easier to qualify than it is with other lenders. In addition to our standard 6-month warranty, we also offer optional extended warranties to help you protect your investment. Our mission is to help you find a quality used semi-truck that meets your needs, and make it faster and easier than ever to get you behind the wheel of that truck.</p>
                    <p>Whether you’re looking for a Mack or a Peterbilt, for a single truck or an entire fleet of them, Arrow Truck Sales can help. Contact us today, and get the help you need in finding the right semi-truck to support your success. Be sure to check out our online tools to get pre-qualified for our in-house financing and receive an estimate on the size of your down payment. Then, stop by our Fontana, CA, location to find the right truck, and let us help you get behind the wheel even sooner.</p>";
                    break;
                case "HS": // Houston, Texas
                    $title     = "<h1 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">About our Houston Location</h1>";
                    $about_1   = "<p class=\"mb-3\">Arrow Truck Sales is saddened to announce that effective July 31, 2023 we have discontinued all selling operations at our three Texas branches in Dallas, Houston and San Antonio.</p>
                    <p class=\"mb-3\">Arrow will continue serving a best-in-class truck buying experience at all of our other United States and Canada locations across North America. Arrow will continue to support our Texas customers on all after-sales situations through our corporate customer service department.</p>
                    <p class=\"mb-3\">Anyone living in Texas can still purchase trucks through our other locations across the country. Online shopping is also available at all locations.</p>";
                    $about_2   = "
                    <p class=\"flex justify-evenly\">
                        <a class=\"btn\" href=\"/search-inventory\" data-arrow-btn=\"true\">
                            Shop Now
                        </a>
                        <br>
                        <a class=\"btn\" href=\"/locations\" data-arrow-btn=\"true\">
                            All Locations
                        </a>
                        <br>
                        <a class=\"btn\" href=\"/used-semi-trailers-for-sale\" data-arrow-btn=\"true\">
                            Contact Us
                        </a>
                    </p>";
                    $about_3   = "<p class=\"mb-3\">Looking for an affordable, reliable semi-truck for sale in Houston, TX? Arrow Truck Sales is a leading retailer of a variety of semi-truck makes and models with locations all around the country. On our lots, you’ll find trucks from major semi-truck manufacturers like Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more. Our used semi-trucks are carefully inspected for safety quality to help ensure that you get a reliable truck that will last you for years—all for a much more affordable price than you can find elsewhere. Check out our existing inventory and if you don’t see what you want, you can speak to a representative to get help with finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Truck You Need Today</h2>
                    <p class=\"mb-3\">At Arrow Truck Sales, we want to get you into a new semi-truck faster, with fewer headaches for you. That’s why we offer easy, flexible financing options on all of our vehicles, and incentives on many of our in-stock trucks. If you’re looking to buy a semi-truck—or an entire fleet of trucks—contact the team at Arrow Truck Sales today, and we’ll help you find the truck you’re looking for. Be sure to check out our online financing tools, so you can get pre-qualified and have an idea of how much of a down payment you’ll need for the truck you want. Give us a call, or stop by one of our locations, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "JX": // Jacksonville, Florida
                    $title     = "<h1 class=\"hdg-3\">Jacksonville Branch</h1>";
                    $about_1   = "<p>Arrow Truck Sales is proud to support Florida truckers and owner-operators by providing access to semi-trucks that are both reliable and affordable. We offer flexible in-house financing that can get you approved faster than any other lender, as well as providing additional insurance coverage options and a built-in 6-month warranty to protect your investment. Our Jacksonville, FL, branch serves truckers throughout the area, including Jacksonville, St. Augustine, Tallahassee, and more. Call or stop by our lot today to see the used semi-trucks in our inventory, and learn more about our in-house financing options and insurance.</p>";
                    $about_2   = "<p>Our Jacksonville branch of Arrow Truck Sales has the knowledgeable and experienced staff members you need—and we are completely committed to the success of your trucking business. Are you looking for a specific make and model of semi-truck for your fleet, or are you looking to explore all of the available options? Our team can help you find that model you’re looking for, or work with you to narrow down your choices and find the perfect truck. Are you just starting your trucking career and looking for a single truck, or do you need several semis to add to your experienced shipping fleet? Whatever you’re searching for, our sales team can help you find the right semis for your unique needs.</p>
                    <p>If you need financing or insurance for your truck purchase, our on-site finance and insurance manager can help. We’ll provide the input you need to choose the right insurance and/or warranties for your semi-trucks, and walk you through the process of applying for our in-house financing. Reach out to us today to speak to our finance and insurance manager, or get prequalified on our website today.</p>";
                    $about_3   = "<p>Are you looking for a used semi-truck for sale in Jacksonville, FL? Buying a used semi from a reputable dealer like Arrow Truck Sales allows you to find a reliable, high-quality semi-truck at an affordable price. You can stick to a budget while growing your trucking business and expanding your career. We are a leading retailer of all major semi-truck manufacturers in the United States, with locations all across the country. Our Jacksonville, FL, location is conveniently located off the I-295 Beltway, making it conveniently accessible to truckers throughout northeastern Florida.</p>
                    <p>Our lot stocks semis from all the major manufacturers in the trucking industry, including Volvo, Kenworth, Peterbilt, International LT, Mac, Freightliner, and more. Before putting a truck on our lot, we perform a thorough inspection to ensure safety and quality, so you can have complete confidence that you’re getting a reliable truck that will last you for years and hundreds of thousands of miles. You can view some of our current inventory below, or contact us to speak to a sales representative and get help finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Right Semi-Truck for Your Career</h2>
                    <p>At Arrow Truck Sales, our goal is to make it faster, easier, and more affordable to purchase a used semi-truck than ever before. That’s why we offer flexible financing options on all of our trucks, and some of the best prices in the industry. Check out our online financing tools, to get pre-qualified and estimate the down payment needed for the truck you want. Whether you’re looking to buy a semi-truck or an entire fleet of trucks, contact the team at Arrow Truck Sales today. Give us a call or stop by our Jacksonville location, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "KC": // Kansas City, Missouri
                    $title     = "<h1 class=\"hdg-3\">Kansas City Branch</h1>";
                    $about_1   = "<p>Here at Arrow Truck Sales, we are committed to helping Kansas City truckers and owner-operators find the reliable semi-trucks they need at an affordable price. We offer flexible in-house financing, while also providing additional insurance and dealership warranties to give you the peace of mind that your investment is covered. Our Kansas City branch’s convenient location serves truckers throughout the area. Call or stop by our lot today to learn more about the used semi-trucks in our inventory, in-house financing options, insurance, and more.</p>";
                    $about_2   = "<p>The team at our Kansas City branch of Arrow Truck Sales has the experience and knowledge you need, and the commitment to your success that your trucking business deserves. Already love a certain make of semi-truck, and looking to upgrade to a more recent model? Want to explore all of the options available in the market? Need a single new truck to start your trucking career, or several to upgrade your fleet?</p>
                    <p>Whatever you’re searching for, our sales team can help you find the right semis for your unique needs. Do you need financing or insurance for your truck purchase? Our on-site finance and insurance manager is ready to help you with activating the right insurance or warranties for your semi-trucks, and walk you through the process of applying for financing with Arrow Truck Sales. Reach out to us today to speak to one of our representatives.</p>";
                    $about_3   = "<p>Looking for a used semi-truck for sale in Kansas City, MO? A used semi is a great way to save money on your business investment, and buying from a reputable dealer like Arrow Truck Sales helps ensure that you’re getting a reliable vehicle. We are a leading retailer of all major semi-truck makes and models in the United States. Our locations are found all across the country, including right here in Kansas City.</p>
                    <p>We carry trucks from major semi-truck manufacturers like Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more—names that you know, recognize, and trust as reliable builders of durable semis. We carefully inspect all the semi-trucks on our lot for safety and quality to help ensure that you get a reliable truck that will last you for years, and at a price that you can afford. Check out our existing inventory at our Kansas City location, or speak to a representative from our sales team to get help finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Right Truck for You</h2>
                    <p>At Arrow Truck Sales, we are committed to making it faster, easier, and more affordable to purchase a used semi-truck. That’s why we provide fast, flexible financing options on all of our trucks, and sale incentives on many of our in-stock semis. Be sure to check out our online financing tools, so you can get pre-qualified and have an idea of how much of a down payment you’ll need for the truck you want. If you’re looking to buy a semi-truck—or an entire fleet of trucks—contact the team at Arrow Truck Sales today. Give us a call, or stop by our Kansas City location, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "NJ": // Newark, New Jersey
                    $title     = "<h1 class=\"hdg-3\">Newark Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we’re proud to be one of the most trusted dealers of used semi-trucks in the country. Truck drivers and owner-operators throughout the Newark area and all across the country have trusted us to provide the quality semi-trucks they need at a price they can afford for years, and that’s a trust we don’t take lightly. We’re committed to making it faster and easier to get into a semi-truck that fits your needs and career path. In addition to carrying trucks from the top manufacturers in the world, we also offer flexible in-house financing, add-on insurance, extended warranties, and other protections for your investment.</p>
                    <p>Our Newark branch is conveniently right off the New Jersey Turnpike, in nearby Elizabeth, NJ. Call or stop by today to explore our inventory of used semi-trucks, or learn more about our financing options, insurance, and other services.</p>";
                    $about_2   = "<p>The Arrow Truck Sales team members at our Newark branch are experienced, knowledgeable, and committed to helping you succeed in the trucking business. Whatever your unique needs and preferences may be in a semi-truck, our sales associates can help you find an affordable used semi that meets those needs. We also have an onsite finance and insurance manager that is available to help you apply for financing, purchase insurance, or add extended warranties to your semi for additional protection. Reach out to us today to speak to one of our representatives. Our multilingual team will be proud to serve you in 12 different languages, including English, Spanish, Italian, Arabic, and more.</p>";
                    $about_3   = "<p>Are you in the market for a used semi-truck for sale in Newark, NJ? Arrow Truck Sales is a leading retailer of used semis from all major semi-truck makes and models, both domestic and international. We have locations across the country, including close to the heart of Newark, in nearby Elizabeth, NJ, and we have served truckers and owner-operators throughout the United States for years. We carry trucks from the most trusted manufacturers in the trucking industry, including Mack, International, Peterbilt, Kenworth, Freightliner, and more. Our used semi-trucks are affordably priced and meticulously inspected to ensure safety, quality, and long-term reliability of your new truck.</p>
                    <p>Visit our lot or view our inventory online to see what we currently have in stock at our Newark location. Don’t see what you want? Contact us to get help finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Let Us Help You Find the Right Semi</h2>
                    <p>At Arrow Truck Sales, we want to get you into a new semi-truck faster, with fewer headaches for you. If you’re looking for a used semi-truck—or several—contact the team at Arrow Truck Sales today. We’ll help you find the right semi for your business and your future. Be sure to get pre-qualified and receive a down payment estimate using our online financing tools. Give us a call or stop by our Newark location. One of our sales representatives will be happy to help you find the semi-truck you need.</p>";
                    break;
                case "PH": // Philadelphia, Pennsylvania
                    $title     = "<h1 class=\"hdg-3\">Philadelphia Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we consider it our mission to help truck drivers and owner-operators of shipping fleets get the quality semi-trucks they need at an affordable price. We offer flexible, in-house financing that makes it easier to qualify, as well as additional insurance and warranties to help you protect your investment. Our Philadelphia, NJ, branch is located right off the NJ Turnpike and I295, making it easier for us to serve truckers throughout the Philadelphia area and all around New Jersey. Give us a call or stop by today to see our inventory, get help with financing and insurance options, and meet our helpful staff.</p>";
                    $about_2   = "<p>Our staff at Arrow Truck Sales make it possible for us to provide both high-quality used semi-trucks and exceptional customer service. Our team is knowledgeable, experienced, and committed to your success. We’re here to guide you throughout the selection process to ensure that you find the semi-truck you need for your career. Then, we’ll help you get the financing and additional protection you need for your new truck. Our onsite finance and insurance manager can help you apply for financing with Arrow Truck Sales and guide you through purchasing any additional warranties you want for your investment.</p>
                    <p>Whether you’re in the market for a single semi-truck or a small fleet, the team at Arrow Truck Sales is here for you. Reach out to us today for friendly, expert service in English, Spanish, and Portuguese.</p>";
                    $about_3   = "<p>If you’re looking for a semi-truck for sale in Philadelphia, NJ, look no further than Arrow Truck Sales. We are a leading retailer of used semi-trucks and carry cabs from all of the major American manufacturers. Here, you’ll find high-quality used semis from recognized brands like Kenworth, Freightliner, Peterbilt, Volvo, and more. We carefully inspect every semi before we list it for sale to ensure that it’s safe, reliable, and up to our high standards of excellence. We’re so confident in the used trucks we sell that we back them with a 6-month warranty to help protect you and your investment. We want to help shipping professionals like you find a reliable truck you can count on for years and at a lower price than you can find anywhere else in Philadelphia.</p>
                    <p>You can check out some of our existing inventory at our Philadelphia, NJ, location below or stop by our lot today. If you don’t see what you’re searching for, contact our team to get help finding the semi-truck you need.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Right Semi-Truck for Your Career</h2>
                    <p>A new semi is more than a truck—it’s an investment into your future. At Arrow Truck Sales, we want to help you ensure that investment is smart, affordable, and certain to pay dividends for your career. That’s why we offer a large selection of quality semis from various manufacturers, along with flexible financing options to get you into your truck faster and additional warranties to help you protect that investment.</p>
                    <p>Whether you’re looking to purchase a single semi-truck or an entire fleet of trucks, contact Arrow Truck Sales today to get help finding the right semi-truck for your career. You can use our online tools to be pre-qualified for our in-house financing and get an estimate on the size of your down payment. Then, stop by our Philadelphia, NJ, used semi lot to find the right truck to launch your trucking career.</p>";
                    break;
                case "PX": // Phoenix, Arizona
                    $title     = "<h1 class=\"hdg-3\">Phoenix Branch</h1>";
                    $about_1   = "At Arrow Truck Sales, we’re driven to help Phoenix truckers and fleet owners find the reliable, high-quality semi-trucks that they need to build their careers in trucking and shipping. Our Phoenix branch is proud to serve our New Mexico community and provide reliable semi-trucks to the truck drivers and haulers who keep America’s supply chains moving. Our flexible financing options make getting the truck (or trucks) easier, and our extended warranties and protection options ensure that your investment is protected. Call or stop by today to learn more about our inventory, financing, insurance, and more.";
                    $about_2   = "<p>Our Phoenix-based team members here at Arrow Truck Sales are incomparable professionals and provide the service, knowledge, and expertise you need to find, finance, and insure your next semi-truck. Know the exact truck you’re looking for? We can help you track it down. Are you still determining the right make and model for you? We’ll help you narrow down your priorities and find a truck that fits them. Need financing or an extended warranty? We’ll help you create a custom financing plan and choose a warranty package that fits your needs. Whether you want a single truck or an entire fleet of trucks, the team at our Phoenix branch is proud to serve you and answer any questions you may have. Reach out to Arrow Truck Sales today to speak to one of our Phoenix team members. Our multilingual team is here to help.</p>";
                    $about_3   = "<p>Looking for semi-trucks for sale in Phoenix, AZ? Our centrally located dealership provides you with access to some of the most recognized and reliable names in semi-truck manufacturing, including Volvo, International LT, Peterbuilt, Freightliner, Kenworth, and more. Our inventory includes manual and automated manual transmissions, day cabs, and extended sleepers.</p>
                    <p>With Arrow Truck Sales, you can find a reliable truck that will last you for years while paying a much lower cost than what you could find at other dealerships. We specialize in dealing with high-quality used semi- trucks and carefully inspect every truck for safety and quality before placing it on our lot. You can check out our current Phoenix inventory below. If you don’t see what you’re looking for, contact our team to speak with a sales representative about finding the right semi-truck for your business.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Truck You Need Today</h2>
                    <p>At Arrow Truck Sales, we want to make it faster and easier than ever to get you into a new semi-truck. We offer easy, flexible financing and frequent sales incentives on many of our in-stock trucks. Whether you’re looking to buy a semi-truck or an entire fleet of trucks, contact the team at Arrow Truck Sales in Phoenix today, and we’ll help you find the truck you’re looking for. Give us a call, or stop by our Phoenix location, and one of our sales representatives will gladly assist you.</p>";
                    break;
                case "SA": // San Antonio, Texas
                    $title     = "<h1 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">About our San Antonio Location</h1>";
                    $about_1   = "<p class=\"mb-3\">Arrow Truck Sales is saddened to announce that effective July 31, 2023 we have discontinued all selling operations at our three Texas branches in Dallas, Houston and San Antonio.</p>
                    <p class=\"mb-3\">Arrow will continue serving a best-in-class truck buying experience at all of our other United States and Canada locations across North America. Arrow will continue to support our Texas customers on all after-sales situations through our corporate customer service department.</p>
                    <p class=\"mb-3\">Anyone living in Texas can still purchase trucks through our other locations across the country. Online shopping is also available at all locations.</p>";
                    $about_2   = "
                    <p class=\"flex justify-evenly\">
                        <a class=\"btn\" href=\"/search-inventory\" data-arrow-btn=\"true\">
                            Shop Now
                        </a>
                        <br>
                        <a class=\"btn\" href=\"/locations\" data-arrow-btn=\"true\">
                            All Locations
                        </a>
                        <br>
                        <a class=\"btn\" href=\"/used-semi-trailers-for-sale\" data-arrow-btn=\"true\">
                            Contact Us
                        </a>
                    </p>";
                    $about_3   = "<p class=\"mb-3\">Looking for an affordable, reliable semi-truck for sale in San Antonio, TX? Arrow Truck Sales is a leading retailer of a variety of semi-truck makes and models with locations all around the country. On our lots, you’ll find trucks from major semi-truck manufacturers like Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more. Our used semi-trucks are carefully inspected for safety quality to help ensure that you get a reliable truck that will last you for years—all for a much more affordable price than you can find elsewhere. Check out our existing inventory and if you don’t see what you want, you can speak to a representative to get help with finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Truck You Need Today</h2>
                    <p class=\"mb-3\">At Arrow Truck Sales, we want to get you into a new semi-truck faster, with fewer headaches for you. That’s why we offer easy, flexible financing options on all of our vehicles, and incentives on many of our in-stock trucks. If you’re looking to buy a semi-truck—or an entire fleet of trucks—contact the team at Arrow Truck Sales today, and we’ll help you find the truck you’re looking for. Be sure to check out our online financing tools, so you can get pre-qualified and have an idea of how much of a down payment you’ll need for the truck you want. Give us a call, or stop by one of our locations, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "SL": // St. Louis, Missouri
                    $title     = "<h1 class=\"hdg-3\">St. Louis Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we’re driven to help St. Louis truckers and fleet owners find the reliable, high-quality semi-trucks that they need to build their career in trucking and shipping. Our flexible financing options make it easier to get the truck (or trucks) you need, and our extended warranties and protection options ensure that your investment is protected. Our St. Louis branch is proud to serve our community and provide reliable semi-trucks to the truck drivers and haulers who keep America’s supply chains moving. Call or stop by today to learn more about our inventory, financing, insurance, and more.</p>";
                    $about_2   = "<p>Our St. Louis-based team members here at Arrow Truck Sales are incomparable professionals, and provide theservice, knowledge, and expertise you need to find, finance, and insure your next semi-truck. Know the exact truck you’re looking for? We can help you track it down. Still determining the right make and model for you? We’ll help you narrow down your priorities and find a truck that fits them. Need financing or an extended warranty? We’ll help you create a custom financing plan and choose a warranty package that fits your needs.</p>
                    <p>Whether you want a single truck or an entire fleet of trucks, the team at our St. Louis branch is proud to serve you and answer any questions you may have. Reach out to Arrow Truck Sales today to speak to one of our St. Louis team members. We’ll be happy to serve you.</p>";
                    $about_3   = "<p>Looking for a used semi-truck for sale in St. Louis, MO? Our conveniently located dealership provides you with access to some of the most recognized and reliable names in semi-truck manufacturing, including Volvo, International LT, Peterbilt, Freightliner, Kenworth, and more. Located in nearby Troy, IL, our St. Louis branch proudly serves customers throughout the greater St. Louis area.</p>
                    <p>We specialize in dealing high-quality used semi-trucks, and carefully inspect every truck for safety and quality before placing it on our lot. With Arrow Truck Sales, you can find a reliable truck that will last you for years while paying a much lower cost than what you could find at other dealerships. You can check out our current St. Louis inventory below. If you don’t see what you’re looking for, contact our team to speak with a sales representative about finding the right semi-truck for your business.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Truck You Need Today</h2>
                    <p>At Arrow Truck Sales, we want to make it faster and easier than ever to get you into a new semi-truck. That’s why we offer easy, flexible financing, and frequent sales incentives on many of our in-stock trucks. Whether you’re looking to buy a semi-truck or an entire fleet of trucks, contact the team at Arrow Truck Sales in St. Louis today, and we’ll help you find the truck you’re looking for. Give us a call, or stop by our St. Louis location, and one of our sales representatives will gladly assist you.</p>";
                    break;
                case "SP": // Springfield, Missouri
                    $title     = "<h1 class=\"hdg-3\">Springfield Branch</h1>";
                    $about_1   = "<p>Arrow Truck Sales is committed to supporting Springfield truckers and owner-operators in finding reliable and affordable semi-trucks. We provide flexible in-house financing, as well as additional insurance coverage and dealership warranties to give you the confidence that your investment is protected. Our Springfield, MO, branch’s convenient location serves truckers throughout the area. Call or stop by our lot today to learn more about the used semi-trucks in our inventory, in-house financing options, insurance, and more.</p>";
                    $about_2   = "<p>The team at our Springfield branch of Arrow Truck Sales has the knowledge and expertise you need, and is completely committed to the success of your trucking business. Already know what make and model of semi-truck you’re looking for? Want to explore all of the options available in the market? Need a single semi-truck to launch your trucking career, or do you need several to add to your shipping fleet? Whatever you’re searching for, our sales team can help you find the right semis for your unique needs.</p>
                    <p>What if you need financing or insurance for your truck purchase? Our on-site finance and insurance manager is available to help you with selecting the right insurance and/or warranties for your semi-trucks. We can also walk you through the process of applying for financing with Arrow Truck Sales. Reach out to us today to speak to our finance and insurance manager, or one of our sales representatives.</p>";
                    $about_3   = "<p>Are you looking for a semi-truck for sale in Springfield, MO? A used semi is a great way to grow your trucking business while sticking to a budget, and buying from a reputable dealer like Arrow Truck Sales helps ensure you’re getting a reliable truck. We are a leading retailer of all major semi-truck makes and models in the United States with locations all across the country. Our Springfield location is conveniently located in nearby Strafford, MO, right off of I-44.</p>
                    <p>We carry trucks from all the major manufacturers in the trucking industry, including Peterbilt, International LT, Volvo, Kenworth, Freightliner, and more. We perform a thorough inspection of every truck on our lot to ensure safety and quality, and give you the confidence that you’re getting a reliable truck that will last you for years—all at a price you can afford. Check out our existing inventory at our Springfield location, or speak to a representative from our sales team to get help finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Right Semi-Truck for You</h2>
                    <p>At Arrow Truck Sales, our goal is to make it faster, easier, and more affordable to purchase a used semi-truck than ever before. That’s why we offer flexible financing options on all of our trucks, and some of the best prices in the industry. Check out our online financing tools, to get pre-qualified and estimate the down payment needed for the truck you want. Whether you’re looking to buy a semi-truck or an entire fleet of trucks, contact the team at Arrow Truck Sales today. Give us a call or stop by our Springfield location, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "ST": // Stockton, California
                    $title     = "<h1 class=\"hdg-3\">Stockton Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we make it faster, easier, and more affordable to find the semi-truck you need. We are committed to helping truck drivers and owner-operators throughout the Stockton area succeed in the trucking industry. That’s why we offer a wide selection of reliable used semi-trucks and flexible financing options as well as optional insurance, built-in warranties, and other protections to give you peace of mind that your investment is covered. Our Stockton branch is conveniently located just off of I-5, in nearby French Camp, CA, and serves truckers throughout the Stockton area. Call or stop by today to learn more about our used semi-trucks, financing, insurance, and more.</p>";
                    $about_2   = "<p>The team at our Stockton location is proud to serve the truck drivers and owner-operators in our community. Our Stockton team at Arrow Truck Sales is committed to your success, and can offer you the knowledge and expertise you need to find the right used semi at the right price for your trucking career. Whether you’re searching for a Volvo, Peterbilt, or Mack truck, or you’re still exploring all of your options, we can help you find the right semi-truck for your needs.</p>
                    <p>Need financing or insurance? Our onsite finance and insurance manager is available to help you find the right insurance and warranties for your semi-truck at an affordable rate, and can guide you through the process to apply for our in-house financing. Reach out to us today to speak to one of our representatives. Our multilingual team will be proud to serve you in English, Spanish, Hindi, or Punjabi.</p>";
                    $about_3   = "<p>Arrow Truck Sales is a trusted retailer of used semi-trucks for all major truck manufacturers. If you’re looking for a used semi-truck for sale in Stockton, CA, make Arrow Truck Sales your first stop. On our lot, you’ll find semi-trucks from major manufacturers like Peterbilt, International, Mack, Kenworth, Freightliner, and more, so you can explore a variety of options to find the ideal fit.</p>
                    <p>We take pride in only selling high-quality, reliable semis, and perform a thorough inspection on every truck we sell to ensure it’s up to our high standards. We’re so confident in the quality of our trucks that we back them all with a 6-month warranty. Check out our existing inventory at our Stockton location by stopping by our lot, or viewing the listings below. Don’t see what you want? Speak to a representative to get help with finding the right semi-truck for your needs.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find Quality Semi-Trucks at Affordable Rates</h2>
                    <p>At Arrow Truck Sales, we aim to help truck drivers and owner-operators find the semi-trucks they need at rates they can afford. Not only do we have some of the best prices in the semi-truck industry, but we also offer in-house financing that makes it faster and easier to qualify for a truck loan, so you can get behind the wheel faster and with fewer headaches.</p>
                    <p>Whether you’re looking to buy a semi-truck or an entire fleet of trucks, contact the team at Arrow Truck Sales today, and we’ll help you find what you need. Be sure to check out our online financing tools, so you can get pre-qualified and receive an estimate or your down payment. Give us a call, or stop by our Stockton location, and one of our sales representatives will be happy to assist you.</p>";
                    break;
                case "TA": // Tampa, Florida
                    $title     = "<h1 class=\"hdg-3\">Tampa Branch</h1>";
                    $about_1   = "<p>At Arrow Truck Sales, we consider it our mission to help truck drivers and owner-operators of shipping fleets get the quality semi-trucks they need at an affordable price. We offer flexible, in-house financing that makes it easier to qualify, as well as additional insurance and warranties to help you protect your investment. Our Tampa, FL, branch is located right off I-4, making it easy for you to access our lot of quality used semi-trucks. Give us a call or stop by today to see our inventory, get help with financing and insurance options, and meet our helpful staff.</p>";
                    $about_2   = "<p>Our staff at Arrow Truck Sales make it possible for us to provide both high-quality used semi-trucks and exceptional customer service. Our team is knowledgeable, experienced, and committed to your success. We’re here to guide you throughout the selection process to ensure that you find the semi-truck you need for your career. Then, we’ll help you get the financing and additional protection you need for your new truck. Our onsite finance and insurance manager can help you apply for financing with Arrow Truck Sales and guide you through purchasing any additional warranties you want for your investment.</p>
                    <p>Whether you’re in the market for a single semi-truck or a small fleet, the team at Arrow Truck Sales is here for you. Reach out to us today for friendly, expert service in English, Bosnian, Serbian, Croatian, or Spanish.</p>";
                    $about_3   = "<p>If you’re looking for a used semi-truck for sale in Tampa, FL, look no further than Arrow Truck Sales. We are a leading retailer of used semi-trucks, and carry cabs from all of the major American manufacturers. Here, you’ll find high-quality used semis from recognized brands like Kenworth, Freightliner, Peterbilt, Volvo, Mack, and more. We carefully inspect every semi before we list it for sale to ensure that it’s safe, reliable, and up to our high standards of excellence. We’re so confident in the used trucks we sell that we back them with a 6-month warranty to help protect you and your investment. We want to help shipping professionals like you find a reliable truck you can count on for years, and at a lower price than you can find anywhere else in Florida.</p>
                    <p>You can check out some of our existing inventory at our Tampa, FL, location below, or stop by our lot today. If you don’t see what you’re searching for, contact our team to get help finding the semi-truck you need.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Right Semi-Truck at the Right Price</h2>
                    <p>A new semi is more than a truck—it’s an investment into your future. At Arrow Truck Sales, we want to help you ensure that investment is smart, affordable, and certain to pay dividends for your career. That’s why we offer a large selection of quality semis from a variety of manufacturers, along with flexible financing options to get you into your truck faster, and additional warranties to help you protect that investment.</p>
                    <p>Whether you’re looking to purchase a single semi-truck or an entire fleet of trucks, contact Arrow Truck Sales today to get help finding the right semi-truck for your career. You can use our online tools to be pre-qualified for our in-house financing and get an estimate on the size of your down payment. Then, stop by our Tampa, FL, used semi lot to find the right truck to launch your trucking career.</p>";
                    break;
                case "OK": // Oklahome City, Oklahoma
                    $title     = "<h1 class=\"hdg-3\">Oklahome City Branch</h1>";
                    $about_1   = "<p>Welcome to Arrow Truck Sales in Oklahoma City, Oklahoma – your trusted partner on the road! Nestled in the heart of America, our Oklahoma City branch is dedicated to empowering truckers and fleet owners with top-notch semi-trucks that stand the test of time and distance.</p>
                    <p>In Oklahoma City, we understand the pulse of Midwestern trucking like no other. Our local branch, conveniently situated, is a hub for truckers traveling through the Great Plains and beyond. We&#39;re not just a dealership; we&#39;re a part of the community, committed to fueling the success of local and regional trucking businesses.</p>";
                    $about_2   = "<p>Our Oklahoma City professionals bring a wealth of knowledge and a passion for trucking that&#39;s unmatched. Looking for a specific make or model? Need help with financing options or choosing the right warranty? Our team is here, ready to guide you every step of the way, ensuring your purchase is as seamless as your drives.</p>";
                    $about_3   = "<p>Our inventory in Oklahoma City boasts an impressive range of semi-trucks, from industry-leading names like Volvo, Peterbilt, Freightliner, and more. Whether you&#39;re searching for a sturdy day cab or a comfortable extended sleeper, manual or automated transmission, we&#39;ve got you covered. And if you can&#39;t find what you&#39;re looking for, let us know – we&#39;re experts at sourcing the right truck for your specific needs.</p>
                    <p>At Arrow Truck Sales, we believe buying a truck should be straightforward and stress-free. That&#39;s why we offer flexible financing solutions tailored to your unique circumstances. Whether you&#39;re buying your first truck or expanding your fleet, our financing options are designed to get you on the road faster and with ease.</p>";
                    $about_4   = "<h2 class=\"hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8\">Find the Right Semi-Truck at the Right Price</h2>
                    <p>Don’t just take our word for it. Come by our Oklahoma City location and see for yourself why Arrow Truck Sales is the go-to choice for truckers across the region. Our doors are always open for you to explore our inventory, meet our team, and find the perfect truck to drive your business forward.</p>";
                    break;
                case "TO": // Toronto, Ontario
                    $title     = null;
                    $about_1   = null;
                    $about_2   = null;
                    $about_3   = null;
                    $about_4   = null;
                    break;
                default:
                    $title     = null;
                    $about_1   = null;
                    $about_2   = null;
                    $about_3   = null;
                    $about_4   = null;
                    break;
            }

            return ( object ) [
                'title'     => $title,
                'about_1'   => $about_1,
                'about_2'   => $about_2,
                'about_3'   => $about_3,
                'about_4'   => $about_4
            ];
        }

        public function get_about_and_seo( $branch_id )
        {
            if ( ! $branch_id ) {
                return null;
            }

            return ArrowParse::location_about( $branch_id );
        }

        public static function format_exerpt( $string )
        {
            $sentences = explode('. ', strtolower( $string ) );

            $counter = 0;

            foreach( $sentences as $sentence ) {
	            $sentences[$counter] = ucfirst( $sentence );
	            $counter++;
            }

            $fixed = implode( '. ', $sentences );

            $format = str_replace( ',', ', ', $fixed );

            $format = str_replace( '.', '. ', $fixed );

            return $format;
        }

        public static function encode_parse( $object )
        {
            return json_encode( $object );
        }

        public static function decode_parse( $object )
        {
            return json_decode( $object );
        }

        // INVENTORY CARD OBJECT
        public static function set_inventory_card( $truck, $media, $post_id )
        {
            $L = new ArrowLocale();

            return (object) [
                'title'         => "{$truck[ 'YEAR' ]} {$truck[ 'MANUFACTURER' ]} {$truck[ 'MODEL' ]}",
                'price'         => $L->price( $truck[ 'PRICE' ], $truck[ 'OLDPRICE' ] )->current->format ?? null,
                'old_price'     => $L->price( $truck[ 'PRICE' ], $truck[ 'OLDPRICE' ] )->old->format ?? null,
                'mileage'       => $L->mileage( $truck[ 'MILEAGE' ] )->format ?? null,
                'ecm'           => $L->ecm( $truck[ 'ECMMILEAGE' ] )->format ?? null,
                'city_state'    => $L->city_state_from_branch( $truck[ 'BRANCHID' ] ) ?? null,
                'image'         => $media->main->image,
                'href'          => get_permalink( $post_id ),
                'inventory_ID'  => $truck[ 'STOCKNUM' ],
                'branch_ID'     => $truck[ 'BRANCHID' ],
                'post_ID'       => $post_id,
                'featured'      => false
            ];
        }

        // INVENTORY DISPLAY OBJECT
        public static function set_inventory_display( $truck, $media, $specs )
        {
            $L = new ArrowLocale();

            return (object) [
                'title'             => "{$truck[ 'YEAR' ]} {$truck[ 'MANUFACTURER' ]} {$truck[ 'MODEL' ]}",
                'headline'          => $truck[ 'INVHEADER' ],
                'details'           => $truck[ 'INVDETAIL' ],
                'engine'            => "{$truck[ 'INVEMAKE' ]} {$truck[ 'INVEMODL' ]}",
                'horsepower'        => "HP: {$truck[ 'INVHPWR' ]}",
                'city_state'        => $L->city_state_from_branch( $truck[ 'BRANCHID' ] ) ?? null,
                'price'             => $L->price( $truck[ 'PRICE' ], $truck[ 'OLDPRICE' ] )->current->format ?? null,
                'old_price'         => $L->price( $truck[ 'PRICE' ], $truck[ 'OLDPRICE' ] )->old->format ?? null,
                'mileage'           => $L->mileage( $truck[ 'MILEAGE' ] )->format ?? null,
                'ecm'               => $L->ecm( $truck[ 'ECMMILEAGE' ] )->format ?? null,
                'main_image'        => $media->main->image,
                'gallery_images'    => json_encode( $media->gallery->image ),
                'video'             => null,
                'specs'             => json_encode( $specs ),
                'button_query'      => json_encode( (object) [
                    'estimate_your_payments'    => http_build_query( [
                                                    'price'     => $truck[ 'PRICE' ],
                                                    'stock'     => $truck[ 'STOCKNUM' ],
                                                    'mileage'   => $truck[ 'MILEAGE' ]
                                                ]),
                    'pre_qualify_for_credit'    => http_build_query( [
                                                    'price'     => $truck[ 'PRICE' ],
                                                    'stock'     => $truck[ 'STOCKNUM' ],
                                                    'mileage'   => $truck[ 'MILEAGE' ]
                                                ]),
                    'contact_us_about_truck'    => http_build_query( [
                                                    'info_1'    => $truck[ 'YEAR' ],
                                                    'info_2'    => $truck[ 'MANUFACTURER' ],
                                                    'info_3'    => $truck[ 'MODEL' ],
                                                    'info_4'    => $truck[ 'STOCKNUM' ],
                                                    'price'     => $truck[ 'PRICE' ],
                                                    'mileage'   => $truck[ 'MILEAGE' ]
                                                ]),
                ]),
                'trailer'           => $truck[ 'MODEL' ] == "Trailer" ? true : false
            ];
        }

        // INVENTORY OBJECT
        public static function inventory( $api_data )
        {
            if ( ! $api_data) {
                return false;
            }

            $L = new ArrowLocale();

            $P = new ArrowParse();

            $current_time   = time();
            $future_time    = $current_time +  (int) $P->refresh_frequency * 60 * 60;

            $truck = ( array ) $api_data;

            $inventory = ( object ) [
                'ID'        => $truck[ 'STOCKNUM' ],
                'active'    => true,
                'type'      => ( $truck[ 'MODEL' ] == 'Trailer' ) ? 'trailer' : 'truck',
                'year'      => $truck[ 'YEAR' ],
                'make'      => $truck[ 'MANUFACTURER' ],
                'model'     => $truck[ 'MODEL' ],
                'title'     => "{$truck[ 'YEAR' ]} {$truck[ 'MANUFACTURER' ]} {$truck[ 'MODEL' ]}",
                'details'   => $truck[ 'INVDETAIL' ],
                'branch_ID' => $truck[ 'BRANCHID' ],
                'post_ID'   => null,
                'card'      => null,
                'display'   => null,
                'media'     => null,
                'specs'     => null,
                'location'  => null,
                'WP'        => null,
                'system'    => (object) [
                    'city'          => null,
                    'state'         => null,
                    'price_data'    => json_encode( $L->price( $truck[ 'PRICE' ], $truck[ 'OLDPRICE' ] ) ),
                    'mileage_data'  => json_encode( $L->mileage( $truck[ 'MILEAGE' ] ) ),
                    'ecm_data'      => json_encode( $L->ecm( $truck[ 'ECMMILEAGE' ] ) ),
                    'specs'         => null,
                    'media'         => null
                ],
                '_api'     => (object) [
                    '_raw_search'   => ll_safe_encode( $truck ), // To decode call ll_safe_decode().
                    '_raw_detail'   => null,
                    '_init'         => 'AUTO - API - SYSTEM', // MANUYAL SYNC - API - JustChad
                    '_last_sync'    => $current_time,
                    '_next_sync'    => $future_time
                ]
            ];

            return $inventory;
        }

        public static function get_object_post_data( $post_id )
        {
            return (object) [
                'ID'        => $post_id,
                'href'      => get_permalink( $post_id ),
                'status'    => get_post_status( $post_id )
            ];
        }

        public static function get_object_user_data( $user_id )
        {
            return (object) [
                'ID'        => $user_id,
                'href'      => get_author_posts_url( $user_id ),
                'status'    => get_userdata( $user_id )->data->user_status
            ];
        }

        public function get_card_photo()
        {
            return "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";
        }

        public static function set_v_card( $rep )
        {
            $P = new ArrowParse();

            $VC = ( object ) [
                'VERSION'       => "3.0",
                'N'             => "{$rep->name->last};{$rep->name->first}",
                'FN'            => "{$rep->name->first} {$rep->name->last}",
                'TITLE'         => $rep->title,
                'PHOTO'         => $P->get_card_photo(),
                'EMAIL'         => $rep->email,
                'ORG'           => get_bloginfo( 'name' ),
                'TEL'           => format_phone( get_field( 'contact_phone_number', 'option' ) ),
                'CELL'          => format_phone( $rep->phone ),
                'URL'           => get_bloginfo( 'url' ),
                'URL_FACEBOOK'  => get_field( 'social_facebook', 'option' ),
                'URL_TWITTER'   => get_field( 'social_twitter', 'option' ),
                'URL_INSTAGRAM' => get_field( 'social_instagram', 'option' ),
                'URL_YOUTUBE'   => get_field( 'social_youtube', 'option' ),
                'URL_BLOG'      => get_field( 'social_blog', 'option' ),
                'BIO'           => $rep->bio
            ];

            $card  = "BEGIN:VCARD" . PHP_EOL;
            $card .= "VERSION:{$VC->VERSION}" . PHP_EOL;
            $card .= $VC->N ? "N:;{$VC->N};;;" . PHP_EOL : null;
            $card .= $VC->FN ? "FN:{$VC->FN}" . PHP_EOL : null;
            $card .= $VC->TITLE ? "TITLE:{$VC->TITLE}" . PHP_EOL : null;
            $card .= $VC->PHOTO ? "PHOTO;ENCODING=b:{$VC->PHOTO}" . PHP_EOL : null;
            $card .= $VC->EMAIL ? "EMAIL;TYPE=INTERNET,PREF:{$VC->EMAIL}" . PHP_EOL : null;
            $card .= $VC->ORG ? "ORG:{$VC->ORG}" . PHP_EOL : null;
            $card .= $VC->TEL ? "TEL;TYPE=WORK:{$VC->TEL}" . PHP_EOL : null;
            $card .= $VC->CELL ? "TEL;TYPE=CELL,VOICE:{$VC->CELL}" . PHP_EOL : null;
            $card .= $VC->URL ? "URL;TYPE=Home:{$VC->URL}" . PHP_EOL : null;
            $card .= $VC->URL_FACEBOOK ? "X-SOCIALPROFILE;TYPE=Facebook:{$VC->URL_FACEBOOK}" . PHP_EOL : null;
            $card .= $VC->URL_TWITTER ? "X-SOCIALPROFILE;TYPE=Twitter:{$VC->URL_TWITTER}" . PHP_EOL : null;
            $card .= $VC->URL_INSTAGRAM ? "X-SOCIALPROFILE;TYPE=Instagram:{$VC->URL_INSTAGRAM}" . PHP_EOL : null;
            $card .= $VC->URL_YOUTUBE ? "X-SOCIALPROFILE;TYPE=YouTube:{$VC->URL_YOUTUBE}" . PHP_EOL : null;
            $card .= $VC->URL_BLOG ? "X-SOCIALPROFILE;TYPE=Blog:{$VC->URL_BLOG}" . PHP_EOL : null;
            $card .= $VC->BIO ? "NOTE;CHARSET=UTF-8:{$VC->BIO}" . PHP_EOL : null;
            $card .= "END:VCARD";

            return trim( $card );
        }

        public static function set_profile( $user_id, $user_slsrepno )
        {
            if ( ! $user_id || ! $user_slsrepno ) {
                return null;
            }

            $default        = 'https://www.arrowtruck.com/images/NoImageHead2.jpg';

            $src_url        = 'https://www.arrowtruckhost.com/images/slsphotos';

            $filename       = "{$src_url}/{$user_slsrepno}/{$user_slsrepno}image1.jpg";

            $file_headers   = @get_headers($filename);

            if ( $file_headers[0] !== 'HTTP/1.1 404 Not Found' && $file_headers[0] !== 'HTTP/1.0 302 Found' && $file_headers[7] !== 'HTTP/1.0 404 Not Found' ) {

                return "{$src_url}/{$user_slsrepno}/{$user_slsrepno}image1.jpg";

            } else {

                return $default;

            }
        }

        // EMPLOYEE DISPLAY OBJECT
        public static function set_employee_display( $rep )
        {
            $L = new ArrowLocale();

            return (object) [
                'ID'
            ];
        }

        // EMPLOYEE CARD OBJECT
        public static function set_employee_card( $rep )
        {
            $L = new ArrowLocale();

            return (object) [
                'ID'        => $rep->ID,
                'user_ID'   => $rep->user_ID,
                'branch_ID' => $rep->branch_ID,
                'name'      => ( object ) [
                    'first'     => $rep->name->first,
                    'last'      => $rep->name->last,
                    'full'      => $rep->name->full
                ],
                'title'     => $rep->title,
                'phone'     => $rep->phone,
                'email'     => $rep->email,
                'languages' => $rep->languages,
                'profile'   => $rep->media,
                'href'      => get_author_posts_url( $rep->user_ID ),
                'member_of' => $rep->system->member_locations,
                'v_card'    => $rep->v_card_href,
            ];
        }

        // EMPLOYEE OBJECT
        public static function employee( $api_data )
        {
            if ( ! $api_data) {
                return false;
            }

            $L = new ArrowLocale();

            $P = new ArrowParse();

            $current_time   = time();
            $future_time    = $current_time +  (int) $P->refresh_frequency * 60 * 60;

            // $rep = ( array ) $api_data[0];
            $rep = ( array ) $api_data;

            $employee = ( object ) [
                'ID'                => $rep[ 'SLSREPNO' ],
                'active'            => $rep[ 'USRSTAT' ] ? true : false,
                'multi_manager'     => null,
                'name'              => ( object ) [
                    'first'                 => $P->format_display_case( $rep[ 'SLSFNAME' ] ?? null ),
                    'last'                  => $P->format_display_case( $rep[ 'SLSLNAME' ] ?? null ),
                    'full'                  => $P->set_full_name( $rep[ 'SLSFNAME' ] ?? null, $rep[ 'SLSLNAME' ] ?? null, )
                ],
                'bio'               => sanitize_textarea_field( $rep[ 'SLSBIO' ] ),
                'email'             => strtolower( $rep[ 'SLSEMAIL' ] ),
                'phone'             => $P->sanitize_phone( $rep[ 'SLSPHONE' ] ?? null ),
                'title'             => $P->set_title( $rep[ 'USRJOBTL' ] ?? null ),
                'role'              => $P->set_role( $rep[ 'USRJOBTL' ] ?? null ),
                'multilingual'      => false,
                'languages'         => $P->set_employee_languages( $rep ),
                'language_array'    => null,
                'branch_ID'         => $rep[ 'SLSBRANCH' ] ?? null,
                'manager'           => $P->set_manager( $rep[ 'USRJOBTL' ] ?? null ),
                'manager_of'        => $P->set_manager_of( $rep[ 'USRJOBTL' ], $rep[ 'SLSEMAIL' ], $rep[ 'SLSREPNO' ] ) ?? null,
                'user_ID'           => null,
                'card'              => null,
                'display'           => null,
                'media'             => null,
                'branch'            => null,
                'v_card'            => null,
                'v_card_href'       => null,
                'WP'                => null,
                'system'            => ( object ) [
                    'group'                 => $rep[ 'USRGROUP' ] ?? null,
                    'multiple_locations'    => ( $rep[ 'MULTIPLELOCATIONS' ] == "Y" ) ? true : false,
                    'member_locations'      => $P->get_member_locations( $rep[ 'SLSEMAIL' ] ),
                    'member_sls'            => $P->get_member_sls( $rep[ 'SLSEMAIL' ] ),
                ],
                '_api'     => (object) [
                    '_raw_search'   => ll_safe_encode( $rep ), // To decode call ll_safe_decode().
                    '_raw_detail'   => null,
                    '_init'         => 'AUTO - API - SYSTEM', // MANUYAL SYNC - API - JustChad
                    '_last_sync'    => $current_time,
                    '_next_sync'    => $future_time
                ]
            ];

            $employee->multilingual = ( count( $employee->languages ) !== 0 ) ? true : false;

            if ( $employee->system->multiple_locations === true && $employee->manager === true ) {
                $employee->multi_manager = $employee->manager_of->branch;
            }

            // if ( $employee->system->multiple_locations === true ) {
            //     $employee->system->member_locations = $P->get_member_locations( $rep[ 'SLSEMAIL' ], $rep[ 'SLSREPNO' ] );
            // }

            return $employee;
        }

        public function get_member_locations( $email )
        {
            if ( ! $email ) {
                return false;
            }

            $query_branches = Arrow()->get_employee_by_email( $email );

            $locations = [];

            foreach( $query_branches as $key => $branch ){
                $locations[] = $branch->BRNBRNID;
            }

            return $locations;
        }

        public function get_member_sls( $email )
        {
            if ( ! $email ) {
                return false;
            }

            $query_branches = Arrow()->get_employee_by_email( $email );

            $sls = [];

            foreach( $query_branches as $key => $branch ){
                $sls[] = $branch->BRNMGR;
            }

            return $sls;
        }

        public function set_manager_of( $title, $email, $sls_number )
        {
            if ( ! $email || ! $sls_number ) {
                return false;
            }

            $branches = Arrow()->get_employee_by_email( $email );

            $branch_data = [];

            $branch_list = [];

            $manager_of = [];

            $admin_of   = [];

            $repeater_list = [];

            foreach( $branches as $key => $branch ){

                $branch_details = (object) [
                    'ID'                => $branch->BRNBRNID,
                    'name'              => $branch->BRNAME,
                    'manager_ID'        => $branch->BRNMGR,
                    'admin_ID'          => $branch->BRNADMIN
                ];

                $repeater_row = [
                    'branch_id'     => $branch->BRNBRNID,
                    'branch_name'   => $branch->BRNAME
                ];

                $repeater_list[] = $repeater_row;

                if ( $branch->BRNMGR == $sls_number ) {
                    $manager_of[] = $branch->BRNBRNID;
                }

                if ( $branch->BRNADMIN == $sls_number ) {
                    $admin_of[] = $branch->BRNBRNID;
                }

                $branch_data[] = $branch_details;

                $branch_list[] = $branch->BRNBRNID;
            }

            return (object) [
                'details'                   => $branch_data,
                'branch'                    => $branch_list,
                'manager_ID'                => $manager_of,
                'admin_ID'                  => $admin_of,
                'manager_type'              => $title,
                'repeater'                  => $repeater_list
            ];
        }

        public function set_manager( $job_title )
        {
            if ( ! $job_title ) {
                return false;
            }

            return ( $job_title == "MGR" || $job_title == "SATMGR" || $job_title == "FIMGR/ADMIN" ) ? true : false;
        }

        public function get_location_languages( $branch_id )
        {
            if ( ! $branch_id ) {
                return false;
            }

            $P = new ArrowParse();

            $location_reps = Arrow()->get_reps_for_location( $branch_id );

            $languages_raw = [];

            $languages = [];

            foreach( $location_reps as $key => $location_rep ) {
                $language = $P->set_employee_languages( (array) $location_rep, true );
                if ( count( $language ) >= 1 ) {
                    foreach( $language as $value ) {
                        $languages[] = $value;
                    }
                }
            }

            return array_unique( $languages );
        }

        public function country_converter( $country_code )
        {
            if ( ! $country_code) {
                return false;
            }

            // The site country code requires it to be 3 characters.
            // Unites States = USA
            // Canada = CAN

            switch ( strtoupper( $country_code ) ) {
                case "USA":
                    $country = "USA";
                    break;
                case "US":
                    $country = "USA";
                    break;
                case "CANADA":
                    $country = "CAN";
                    break;
                case "CA":
                    $country = "CAN";
                    break;
                case "TO":
                    $country = "CAN";
                    break;
                default:
                    $country = ARROW_COUNTRY;
                    break;
            }

            return ( strlen( $country ) === 3 ) ? $country : false;;
        }

        // LOCATION OBJECT
        public static function location( $api_data )
        {
            if ( ! $api_data) {
                return false;
            }

            $L = new ArrowLocale();

            $P = new ArrowParse();

            $current_time   = time();
            $future_time    = $current_time +  (int) $P->refresh_frequency * 60 * 60;

            $branch = ( array ) $api_data;
            // $branch = ( array ) $api_data[0];

            $location = ( object ) [
                'ID'                    => $branch[ 'BRNBRNID' ] ?? null,
                'active'                => $P->set_and_equal_to( $branch[ 'ACTIVE' ], "Y" ) ? true : false,
                'name'                  => $P->set_with_value( $branch[ 'BRNAME' ] ) ? $P->format_display_case( $branch[ 'BRNAME' ] ) : null,
                'image'                 => get_template_directory_uri() . "/assets/img/default_LOCATION.jpg",
                'manager'               => $branch[ 'BRNMGR' ] ?? null,
                'admin'                 => $branch[ 'BRNADMIN' ] ?? null,
                'hours'                 => $P->location_hours( $branch[ 'BRNBRNID' ] ),
                'languages'             => $P->get_location_languages( $branch[ 'BRNBRNID' ] ),
                'country'               => $P->country_converter( $branch[ 'BRNCNTRY' ] ?? null ),
                'address'               => ( object ) [
                    'line_1'                => $P->set_with_value( $branch[ 'BRNADDR1' ] ) ? strtoupper( $branch[ 'BRNADDR1' ] ) : null,
                    'line_2'                => $P->set_with_value( $branch[ 'BRNADDR2' ] ) ? strtoupper( $branch[ 'BRNADDR2' ] ) : null,
                    'city'                  => $P->set_with_value( $branch[ 'BRNCITY' ] ) ? $P->format_display_case( $branch[ 'BRNCITY' ] ) : null,
                    'state'                 => strtoupper( $branch[ 'BRNSTATE' ] ) ?? null,
                    'zip'                   => $branch[ 'BRNZIP' ] ?? null,
                    'country'               => $P->country_converter( $branch[ 'BRNCNTRY' ] ?? null ),
                    'city_state'            => $P->set_with_value( $branch[ 'BRNCITYSTATE' ] ?? null ) ? $P->format_display_case( $branch[ 'BRNCITYSTATE' ] ?? null ) : null,
                    'exit'                  => $P->set_with_value( $branch[ 'EXIT' ] ) ? strtoupper( $branch[ 'EXIT' ] ) : null,
                    'geo'                   => $P->set_lat_long( $branch[ 'BRNCNTRY' ] ?? null, $branch[ 'BRNZIP' ] ?? null ), // Requires Admin enable.
                    'link'                  => $P->set_map_address( $branch[ 'BRNADDR1' ] ?? null, $branch[ 'BRNCITY' ] ?? null, $branch[ 'BRNSTATE' ] ?? null, $branch[ 'BRNZIP' ] ?? null )
                ],
                'contact'               => ( object ) [
                    'email'                 => null,
                    'phone_1'               => $P->set_with_value( $branch[ 'BRNPHONE' ] ) ? $P->sanitize_phone( $branch[ 'BRNPHONE' ] ) : null,
                    'phone_2'               => $P->set_with_value( $branch[ 'PHONEFI' ] ) ? $P->sanitize_phone( $branch[ 'PHONEFI' ] ) : null,
                    'fax'                   => $P->set_with_value( $branch[ 'BRNFAX' ] ) ? $P->sanitize_phone( $branch[ 'BRNFAX' ] ) : null,
                    'toll_free_1'           => $P->set_with_value( $branch[ 'PHONE' ] ) ? $P->sanitize_phone( $branch[ 'PHONE' ] ) : null,
                    'toll_free_2'           => $P->set_with_value( $branch[ 'PHONE2' ] ) ? $P->sanitize_phone( $branch[ 'PHONE2' ] ) : null
                ],
                'reps'                  => $P->get_location_reps( $branch[ 'BRNBRNID' ] ),
                'featured_inventory'    => null,
                'WP'                    => null,
                'about_and_seo'         => ll_safe_encode( $P->get_about_and_seo( $branch[ 'BRNBRNID' ] ) ),
                'seo_meta'              => $P->get_location_meta( $branch[ 'BRNCITYSTATE' ] ),
                'system'                => ( object ) [
                    'name'                  => $branch[ 'BRNNAME' ] ?? null,
                    'sales_tax'             => $branch[ 'BRNSLSTX' ] ? "{$branch[ 'BRNSLSTX' ]}%" : null,
                    'inspection_fee'        => $P->set_with_value( $branch[ 'BRNDFT1' ] ) ? number_format( $branch[ 'BRNDFT1' ], 2 ) : null,
                    'dock_fee'              => $P->set_with_value( $branch[ 'BRNDFT2' ] ) ? number_format( $branch[ 'BRNDFT2' ], 2 ) : null
                ],
                '_api'     => (object) [
                    '_raw_search'       => ll_safe_encode( $branch ), // To decode call ll_safe_decode().
                    '_init'             => 'AUTO - API - SYSTEM', // MANUYAL SYNC - API - JustChad
                    '_last_sync'        => $current_time,
                    '_next_sync'        => $future_time
                ]
            ];

            return $location;
        }

        public static function get_location_meta( $city_state )
        {
            if ( ! $city_state ) {
                return false;
            }

            $city = explode( ', ', $city_state )[0];
            $state = explode( ', ', $city_state )[1];

            $title          = "{$city_state} Semi Trucks for Sale | Arrow Truck Sales, Inc.";
            $keyphrase      = "arrow truck sales {$city}";
            $author         = "Arrow Truck Sales";
            $description    = "Looking to buy new and used semi trucks in {$city_state}? Visit Arrow Truck Sales, INC today!";
            $acf            = get_field( 'location_seo_global_defaults', 'option' ) ?? null;

            if ( $acf ) {
                $title          = ( $acf[ 'meta_title' ] ) ? str_replace( '[city-state]', $city_state, $acf[ 'meta_title' ] ) : null;
                $keyphrase      = ( $acf[ 'meta_keyphrase' ] ) ? str_replace( '[city-state]', $city , $acf[ 'meta_keyphrase' ] ) : null;
                $author         = $acf[ 'meta_author' ];
                $description    = ( $acf[ 'meta_description' ] ) ? str_replace( '[city-state]', $city_state, $acf[ 'meta_description' ] ) : null;
            }

            $location_meta = ( object ) [
                'meta_title'        => $title,
                'meta_keyphrase'    => $keyphrase,
                'meta_author'       => $author,
                'meta_description'  => $description
            ];

            return $location_meta;
        }

        public function get_location_reps( $branch_id )
        {
            if ( ! $branch_id ) {
                return false;
            }

            $location_reps = Arrow()->get_reps_for_location( $branch_id );

            $reps = [];

            foreach( $location_reps as $key => $location_rep ) {
                $reps[] = $location_rep->SLSREPNO;
            }

            return $reps;
        }

        public static function location_details( $location )
        {
            return "<a href=\"{$location->address->link}\" class=\"inline-block hover:underline\" target=\"_blank\">
                        <address class=\"not-italic font-bold mt-3 text-gray-400\">
                            <span class=\"block\">{$location->address->line_1}</span>
                            <span class=\"block]\">{$location->address->city}, {$location->address->state} {$location->address->zip}</span>
                        </address>
                    </a>
                    <p class=\"text-sm mt-2\">{$location->address->exit}</p>";
        }

        public static function set_media( $truck )
        {
            $L = new ArrowLocale();

            $media = ( object ) [
                'image'     => $L->image( $truck[ 'PHOTOPATH' ], $truck[ 'PHOTONUM' ], $truck[ 'INVIMAGE' ], $truck[ 'ISAPPRAISALPHOTO' ] ),
                'video'     => $L->video( $truck[ 'VIDEOPATH' ], $truck[ 'HASVIDEO' ] ),
                'doc'       => $L->doc( $truck[ 'DOCPATH' ], $truck[ 'HASDOT' ] ),
                'fleet'     => ( $truck[ 'ISAPPRAISALPHOTO' ] === 'Y' ) ? true : false
            ];

            return $media;
        }

        public static function set_specs( $truck )
        {
            $set_specs = [
                'engine'        => [
                    'manufacturer'      => ( object ) [
                        'title'             => "Manfacturer",
                        'value'             => $truck[ 'ENGINEMAKE' ] ?? null
                    ],
                    'model'             => ( object ) [
                        'title'             => "Model",
                        'value'             => $truck[ 'ENGINEMODEL' ] ?? null
                    ],
                    'horsepower'        => ( object ) [
                        'title'             => "Horsepower",
                        'value'             => $truck[ 'HORSEPOWER' ] ?? null
                    ]
                ],
                'transmission'  => [
                    'speed'             => ( object ) [
                        'title'             => "Speed",
                        'value'             => $truck[ 'TRANSPEED' ] ?? null
                    ],
                    'type'              => ( object ) [
                        'title'             => "Type",
                        'value'             => $truck[ 'TRANSTYPE' ] ?? null
                    ],
                    'name'              => ( object ) [
                        'title'             => "Name",
                        'value'             => $truck[ 'TRANSNAME' ] ?? null
                    ]
                ],
                'wheels_tires'  => [
                    'axle'              => ( object ) [
                        'title'             => "Axle",
                        'value'             => $truck[ 'AXLE' ] ?? null
                    ],
                    'ratio'             => ( object ) [
                        'title'             => "Ratio",
                        'value'             => $truck[ 'RATIO' ] ?? null
                    ],
                    'suspension'        => ( object ) [
                        'title'             => "Suspension",
                        'value'             => $truck[ 'SUSPENSION' ] ?? null
                    ],
                    'wheelbase'         => ( object ) [
                        'title'             => "Wheelbase",
                        'value'             => $truck[ 'WHEELBASE' ] ?? null
                    ],
                    'steer_wheel_type'  => ( object ) [
                        'title'             => "Steer Wheel Type",
                        'value'             => $truck[ 'STEERWHEEL' ] ?? null
                    ],
                    'rear_wheel_type'   => ( object ) [
                        'title'             => "Rear Wheel Type",
                        'value'             => $truck[ 'REARWHEEL' ] ?? null
                    ],
                    'front_axle'        => ( object ) [
                        'title'             => "Front Axle (Lbs.)",
                        'value'             => number_format( (int) $truck[ 'FRONTAXLE' ] ) ?? null
                    ],
                    'rear_axle'         => ( object ) [
                        'title'             => "Rear Axle (Lbs.)",
                        'value'             => number_format( (int) $truck[ 'REARAXLE' ] ) ?? null
                    ],
                    'tire_size'         => ( object ) [
                        'title'             => "Tire Size",
                        'value'             => $truck[ 'TIRESIZE' ] ?? null
                    ]
                ],
                'details'       => [
                    'sleeper_type'      => ( object ) [
                        'title'             => "Sleeper Type",
                        'value'             => $truck[ 'SLEEPERTYPE' ] ?? null
                    ],
                    'sleeper_size'      => ( object ) [
                        'title'             => "Sleeper Size",
                        'value'             => $truck[ 'SLEEPERSIZE' ] ?? null
                    ],
                    'num_bunks'         => ( object ) [
                        'title'             => "No. of Bunks",
                        'value'             => $truck[ 'BUNKSNUM' ] ?? null
                    ],
                    'fairings'          => ( object ) [
                        'title'             => "Fairings",
                        'value'             => $truck[ 'FAIRINGS' ] ?? null
                    ],
                    'apu'          => ( object ) [
                        'title'             => "APU (Y/N)",
                        'value'             => $truck[ 'APU' ] ?? null
                    ],
                    'jake_brake'        => ( object ) [
                        'title'             => "Jake Brake (Y/N)",
                        'value'             => $truck[ 'JAKEBRAKE' ] ?? null
                    ],
                    'fifth_wheel'       => ( object ) [
                        'title'             => "5th Wheel",
                        'value'             => $truck[ 'FIFTHWHEEL' ] ?? null
                    ],
                    'ecm_mileage'       => ( object ) [
                        'title'             => "ECM Mileage",
                        'value'             => number_format( (int) $truck[ 'ECMMILEAGE' ] )
                    ],
                    'brakes'            => ( object ) [
                        'title'             => "Brakes",
                        'value'             => $truck[ 'BRAKES' ] ?? null
                    ],
                    'tank_capacity'     => ( object ) [
                        'title'             => "Fuel Tank Capacity",
                        'value'             => $truck[ 'TANKCAPACITY' ] ?? null
                    ],
                    'tank_quantity'     => ( object ) [
                        'title'             => "Fuel Tank Quantity",
                        'value'             => $truck[ 'TANKQUANTITY' ] ?? null
                    ]
                ]
            ];

            return $set_specs;
        }

        public function set_employee_languages( $rep, $array = false )
        {
            if( ! $rep ){
                return [];
            }

            $languages = [
                $rep[ 'SLSLANG1' ] ?? null,
                $rep[ 'SLSLANG2' ] ?? null,
                $rep[ 'SLSLANG3' ] ?? null,
                $rep[ 'SLSLANG4' ] ?? null,
                $rep[ 'SLSLANG5' ] ?? null,
                $rep[ 'SLSLANG6' ] ?? null,
                $rep[ 'SLSLANG7' ] ?? null,
                $rep[ 'SLSLANG8' ] ?? null,
                $rep[ 'SLSLANG9' ] ?? null,
                $rep[ 'SLSLANG10' ] ?? null
            ];

            $language_list = [];

            $language_array = [];

            foreach ( $languages as $language ) {
                if ( $language ) {
                    $format = ucfirst( strtolower( $language ) );
                    $flag = get_stylesheet_directory() . "/assets/img/flags/{$language}.svg";

                    $language_list[] = ( object ) [
                        "name"  => $format,
                        "flag"  => $flag
                    ];

                    $language_array[] = strtolower( $format );
                }
            }

            if ( $array ) {
                return $language_array;
            }

            return $language_list;
        }

        public function set_employee_image( $rep_number )
        {
            if ( ! $rep_number ) {
                return null;
            }

            return "{$this->employee_image_path}/{$rep_number}/{$rep_number}image1.jpg";
        }

        public function set_full_name( $first, $last )
        {
            if ( ! $first || ! $last ) {
                return null;
            }

            $P = new ArrowParse();

            $first  = $P->format_display_case( $first );
            $last   = $P->format_display_case( $last );

            return "{$first} {$last}";
        }

        public function set_role( $title )
        {
            if ( ! $title ) {
                return null;
            }

            switch ( $title ) {
                case "FIMGR/ADMIN":
                    $employee_role = "arrow_fandi_manager";
                    break;

                case "MGR":
                case "SATMGR":
                    $employee_role = "arrow_branch_manager";
                    break;

                case "ASTMGR":
                    $employee_role = "arrow_assistant_branch_manager";
                    break;

                case "SALESMGR":
                case "SALES MANAGER":
                    $employee_role = "arrow_sales_manager";
                    break;

                case "SALES/PURCH MGR":
                    $employee_role = "arrow_sales_purchasing_manager";
                    break;

                case "LEAD SALE ASSC":
                    $employee_role = "arrow_lead_sales_associate";
                    break;

                case "SALES":
                    $employee_role = "arrow_retail_sales_consultant";
                    break;

                case "ADMIN":
                case "ADMIN ASSIST":
                    $employee_role = "arrow_admin_assistant";
                    break;

                case "SHOP":
                case "SHOP2":
                    $employee_role = "arrow_inventory_coordinator";
                    break;

                case "BUYERS":
                    $employee_role = "arrow_buyer";
                    break;

                default:
                    $employee_role = "arrow_sales_rep";
            }

            return $employee_role;
        }

        public function set_title( $title )
        {
            if ( ! $title ) {
                return null;
            }

            switch ( $title ) {
                case "BUYERS":
                    $job_title = "Buyer";
                    break;

                case "MGR":
                case "SATMGR":
                    $job_title = "Branch Manager";
                    break;

                case "FIMGR/ADMIN":
                    $job_title = "Finance and Insurance Manager";
                    break;

                case "ASTMGR":
                    $job_title = "Assistant Branch Manager";
                    break;

                case "SALESMGR":
                case "SALES MANAGER":
                    $job_title = "Sales Manager";
                    break;

                case "SALES/PURCH MGR":
                    $job_title = "Sales and Purchasing Manager";
                    break;

                case "LEAD SALE ASSC":
                    $job_title = "Lead Sales Associate";
                    break;

                case "SALES":
                    $job_title = "Retail Sales Consultant";
                    break;

                case "ADMIN":
                case "ADMIN ASSIST":
                    $job_title = "Administrative Assistant";
                    break;

                case "SHOP":
                case "SHOP2":
                    $job_title = "Inventory Coordinator";
                    break;

                default:
                    $job_title = $title;
            }

            return $job_title;
        }

        public function format_display_case( $string )
        {
            return ucwords( strtolower( $string ) );
        }

        public function set_lat_long( $branch_id )
        {
            if( ! $branch_id ){
                return null;
            }

            switch ( $branch_id ) {
                case "AT": // Atlanta, Georgia
                    $longitude = -84.386330;
                    $latitude  = 33.753746;
                    break;
                case "CH": // Chicago, Illinois
                    $longitude = 41.881832;
                    $latitude  = -87.623177;
                    break;
                case "CN": // Cincinnati, Ohio
                    $longitude = 39.103119;
                    $latitude  = -84.512016;
                    break;
                case "DA": // Dallas, Texas
                    $longitude = 32.779167;
                    $latitude  = -96.808891;
                    break;
                case "FR": // Fresno, California
                    $longitude = 36.746841;
                    $latitude  = -119.772591;
                    break;
                case "FT": // Fontana, California
                    $longitude = 34.092232;
                    $latitude  = -117.435051;
                    break;
                case "HS": // Houston, Texas
                    $longitude = 29.749907;
                    $latitude  = -95.358421;
                    break;
                case "JX": // Jacksonville, Florida
                    $longitude = 30.332184;
                    $latitude  = -81.655647;
                    break;
                case "KC": // Kansas City, Missouri
                    $longitude = 39.099724;
                    $latitude  = -94.578331;
                    break;
                case "NJ": // Newark, New Jersey
                    $longitude = 40.735657;
                    $latitude  = -74.172363;
                    break;
                case "PH": // Philadelphia, Pennsylvania
                    $longitude = 39.952583;
                    $latitude  = -75.165222;
                    break;
                case "PX": // Phoenix, Arizona
                    $longitude = 33.448376;
                    $latitude  = -112.074036;
                    break;
                case "SA": // San Antonio, Texas
                    $longitude = 29.424349;
                    $latitude  = -98.491142;
                    break;
                case "SL": // St. Louis, Missouri
                    $longitude = 38.627003;
                    $latitude  = -90.199402;
                    break;
                case "SP": // Springfield, Missouri
                    $longitude = 37.210388;
                    $latitude  = -93.297256;
                    break;
                case "ST": // Stockton, California
                    $longitude = 37.961632;
                    $latitude  = -121.275604;
                    break;
                case "TA": // Tampa, Florida
                    $longitude = 27.964157;
                    $latitude  = -82.452606;
                    break;
                case "TO": // Toronto, Ontario
                    $longitude = 43.653908;
                    $latitude  = -79.384293;
                    break;
                default: // Kansas City, Missouri
                    $longitude = 39.099724;
                    $latitude  = -94.578331;
            }

            return ( object ) [
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'lat_long'  => "{$latitude},{$longitude}"
            ];
        }

        public function set_map_address( $street, $city, $state, $zip )
        {
            if ( ! $street || ! $city || ! $state || ! $zip ) {
                return null;
            }

            $address = "{$street}, {$city}, {$state} {$zip}";
            $new_address = sanitize_title( $address );
            $new_address = str_replace('-', '+', $new_address);

            return "https://www.google.com/maps?q={$new_address}";
        }

        public function set_with_value( $variable )
        {
            if ( isset( $variable ) && ! empty( $variable ) ) {
                return true;
            }

            return false;
        }

        public function set_and_equal_to( $variable, $value )
        {
            // set_and_equal_to( $array[ 'key' ], "VALUE" );
            if ( ( isset( $variable ) ? $variable : null ) == $value ) {
                return true;
            }

            return false;
        }

        public function sanitize_phone( $string )
        {
            $phone = preg_replace( "/[^0-9]/", "", $string );
            return $phone;
        }

    }

    // *** INVENTORY OBJECT ***
    // ----------------------------------------------------------------
    //
    // [
    //     [
    //         {
    //             "STOCKNUM":"262947",
    //             "YEAR":"2020",
    //             "MANUFACTURER":"Freightliner",
    //             "MODEL":"Cascadia Evolution Next Gen",
    //             "PRICE":"62950.00",
    //             "MILEAGE":"473837",
    //             "LOCATION":"Chicago",
    //             "STATE":"IL",
    //             "BRANCHID":"CH",
    //             "ISAPPRAISALPHOTO":"",
    //             "HASPHOTO":"Y",
    //             "PHOTOPATH":"https://www.arrowtruckhost.com/invImages/001_262947/",
    //             "PHOTONUM":"10",
    //             "PRICECHANGED":"",
    //             "OLDPRICE":"",
    //             "ECMMILEAGE":"0",
    //             "INVSTKNO":"262947",
    //             "INVYEAR":"2020",
    //             "INVMAKE":"FL",
    //             "INVMODL":"CASCADEVO1",
    //             "EQUDESCR":"Freightliner",
    //             "EQMDESCR":"Cascadia Evolution Next Gen",
    //             "INVPRICE":"62950.00",
    //             "INVMILAG":"473837",
    //             "BRANCHNAME":"CHICAGO",
    //             "BRNSTATE":"IL",
    //             "INVBRNID":"CH",
    //             "INVSTAT":"H",
    //             "INVINVTY":"S",
    //             "INVCNSGN":"N",
    //             "INVEMCM":"0",
    //             "INVEMAKE":"DET",
    //             "INVEMODL":"DD15",
    //             "INVHPWR":"400",
    //             "INVTRANS":"DETROIT",
    //             "INVTMODL":"DT12-DB-1550",
    //             "INVTSPD":"12",
    //             "INVTRNTY":"A",
    //             "INVAXLE":"T",
    //             "INVSLPR":"RAISEDROOF",
    //             "INVSLPSZ":"72",
    //             "INVSUSP":"A/RIDE",
    //             "INVBDTYP":"TRAC",
    //             "INVSPCD":"",
    //             "INVFLEET":"CRETETF",
    //             "INVOWRNT":"",
    //             "INVLSTST":"Y",
    //             "INVCLASS":"8",
    //             "INVIMAGE":"Y",
    //             "INVPCKAG":"10",
    //             "INVAPRNO":"2088007",
    //             "INVNDLST":"",
    //             "INVCAT2":"",
    //             "INVCAT3":"A3",
    //             "INVAGEDT":"20240109",
    //             "INVHSTKNO":"262947",
    //             "INVHEADER":"WON'T LAST,DD15,DT12 AUTO,VERY LOW MILES",
    //             "INVDETAIL":"DETROIT DD15,DT12 AUTO TRANSMISSION,AIRIDE SUSP,AIRSLIDE 5TH WHEEL,WHEEL TO WHEEL SKIRTS,DUAL HI BACK AIRIDE CAPTAIN SEATS,DOUBLE BUNK SLPR,PWR DOOR LOCKS & WINDOWS,TILT/TELE STEERING COLUMN,10 ALUMINUM WHEELS,COLLISION MITIGATION,VERY LOW MILES,TRUCK WON'T LAST LONG,CALL/CLICK TODAY!!!",
    //             "INVYOUTUBE":"",
    //             "INVVIDEO":"",
    //             "INVDOT":"",
    //             "INVECM":"",
    //             "INVRIGDIG":"",
    //             "INVBUYNOW":"",
    //             "FID":"",
    //             "FNUM":"",
    //             "FACTIVE":"",
    //             "FPICS":"",
    //             "EQUMAKE":"FL",
    //             "EQUDESCR1":"Freightliner",
    //             "EQMMODL":"CASCADEVO1",
    //             "EQMDESCR1":"Cascadia Evolution Next Gen",
    //             "EQMKASSC":"FL",
    //             "EQMUSRST":"",
    //             "EQMCHGDT":"0",
    //             "BODTLFLG":""
    //         }
    //     ]
    // ]

    // *** LOCATION OBJECT ***
    // ----------------------------------------------------------------
    //
    // [
    //     [
    //         {
    //             "BRNBRNID":"SL", //
    //             "BRNNAME":"ATS - ST LOUIS", //
    //             "BRNADDR1":"2100 LIEBLER ROAD", //
    //             "BRNADDR2":"", //
    //             "BRNCITY":"TROY", //
    //             "BRNSTATE":"IL", //
    //             "BRNZIP":"62294", //
    //             "BRNCNTRY":"USA", //
    //             "BRNADMIN":"2123", // X
    //             "BRNMGR":"3558", //
    //             "BRNNPRG":"365", // X
    //             "BRNFTCK":"15", // X
    //             "BRNPHONE":"6186671236", //
    //             "BRNFAX":"6186678176", //
    //             "BRNSLSTX":"6.25000", //
    //             "BRNEXRTE":"0.00000", // X
    //             "BRNTXT1":"Inspec Fee", // X
    //             "BRNDFT1":"250.00", //
    //             "BRNTXT2":"Doc Fee", // X
    //             "BRNDFT2":"75.00", //
    //             "BRNINVNO":"200", // X
    //             "BRNINVAM":"13000000.00", // X
    //             "BRNOVRID":"", // X
    //             "BRNCUNTS":"44", // X
    //             "BRNCINAM":"1570824.26", // X
    //             "BRNMSGPC":"0.9000", // X
    //             "BRNBRNTY":"", // X
    //             "BRNUSER":"THOMPSONA", // X
    //             "BRNWSID":"QPADEV001V", // X
    //             "BRNTSTMP":"1/25/2024 10:10:43 AM", // X
    //             "BRNPRT":"STLASERFX", // X
    //             "BRNREG":"VULPONE", // X
    //             "BRNRPTUN":"85", // X
    //             "BRNRPTMX":"2786000.00", // X
    //             "BRNDEAL":"", // X
    //             "BRNACTID":"15", // X
    //             "BRNWDAMT":"700.00", // X
    //             "BRANCH":"SL", // X
    //             "PHONE":"800-827-7695", //
    //             "PHONE2":"888-215-1247", //
    //             "EXIT":"I-55, EXIT 18", //
    //             "ACTIVE":"Y", //
    //             "PHONEFI":"618-667-4040",
    //             "BRNAME":"St. Louis", //
    //             "STATUS":"", // X
    //             "STATE":"MO", // X
    //             "BRNCITYSTATE":"St. Louis, MO" //
    //         }
    //     ]
    // ]


    // *** EMPLOYEE OBJECT ***
    // ----------------------------------------------------------------
    //
    // [
    //     [
    //         {
    //             "SLSREPNO":"2965", //
    //             "SLSREPBR":"ATS - CINCINNATI", //
    //             "SLSBRANCH":"CN", //
    //             "SLSFNAME":"CHARLIE", //
    //             "SLSLNAME":"COLYER", //
    //             "SLSEMAIL":"CCOLYER@ARROWTRUCK.COM", //
    //             "SLSPHONE":"", //
    //             "SLSBIO":"Hello, My name is Charlie Colyer and I am happy to part of the management team here at Arrow Truck Sales Cincinnati. With my past ownership in the truck and trailer repair business for 39 plus years I understand providing a great product at a fair price. I would love the opportunity to earn your business so please don''t hesitate to give me a call!", //
    //             "SLSLANG1":"", //
    //             "SLSLANG2":"", //
    //             "SLSLANG3":"", //
    //             "SLSLANG4":"", //
    //             "SLSLANG5":"", //
    //             "SLSLANG6":"", //
    //             "SLSLANG7":"", //
    //             "SLSLANG8":"", //
    //             "SLSLANG9":"", //
    //             "SLSLANG10":"", //
    //             "USRUSER":"SLS2965", //
    //             "USREMPNO":"2965", //
    //             "USRADDR1":"CHARLIE COLYER", //
    //             "USRCLASS":"BM", //
    //             "USRGROUP":"ADMIN", //
    //             "USRBRNCH":"CN", //
    //             "USRSTAT":"A", //
    //             "USRTDATE":"0", //
    //             "USREMAIL":"CCOLYER@ARROWTRUCK.COM", // X
    //             "USRJOBTL":"MGR", //
    //             "MULTIPLELOCATIONS":"N" //
    //         }
    //     ]
    // ]
