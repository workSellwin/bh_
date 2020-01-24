<style>
    a.lorealprofessionnel {
        background-color: transparent;
        max-height: 650px;
    }

    a.lorealprofessionnel img {
        margin: 0 auto;
        display: block;
        max-height: 600px;
    }

    .fancybox-close-small {
        top: -15px;
    }

    @media only screen and (min-width: 1000px) {
        a.lorealprofessionnel {
            max-width: 70%;
        }
    }

    @media only screen and (min-width: 1150px) {
        a.lorealprofessionnel {
            max-width: 65%;
        }
    }

    @media only screen and (min-width: 1300px) {
        a.lorealprofessionnel {
            max-width: 60%;
        }
    }

    @media only screen and (min-width: 1500px) {
        a.lorealprofessionnel {
            max-width: 55%;
        }
    }

    @media only screen and (min-width: 1700px) {
        a.lorealprofessionnel {
            max-width: 50%;
        }
    }
</style>
<script>
    let sectionCode = '<?php echo $arResult["VARIABLES"]["SECTION_CODE"]?>';

    document.addEventListener('DOMContentLoaded', function () {
        if (sectionCode == 'professionnel') {
            setTimeout(function () {
                $.fancybox.open(
                    '<a class="lorealprofessionnel" href="https://lorealprofessionnel.ru/diagnostic-bh" target="_blank">' +
                    '   <img src="/upload/0ab21eb7-d598-4c11-9d5e-9856c86f8126.webp" alt=""/>' +
                    '</a>'
                );
            }, 2000);
        }
    });

</script>
