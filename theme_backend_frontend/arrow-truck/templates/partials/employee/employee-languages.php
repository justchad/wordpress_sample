<div class="languages mt-8">
    <h2 class="paragraph-large font-bold mb-3 text-center">
        Multilingual
    </h2>
    <div class="max-4-col mx-auto bg-brand-light-gray grid gap-4 py-0 px-6 text-center rounded-md p-6 rounded-md">
        <ul class="language-list text-center -mx-3 my-5 flex flex-wrap justify-center">

            <?php foreach ( $employee->languages as $language ) : ?>

                <li class="text-sm text-gray-300 text-center mx-3 my-2">
                    <div class="font-medium">

                        <?php echo $language->name; ?>

                    </div>
                </li>

            <?php endforeach; ?>

        </ul>
    </div>
</div>
