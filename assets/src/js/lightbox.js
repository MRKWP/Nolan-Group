const getAllMrkLightBoxes = document.querySelectorAll('mrkwp-lightbox');

if(getAllMrkLightBoxes.length > 0) {
    getAllMrkLightBoxes.forEach((getAllMrkLightBox, index) => {
        let is_mrk_lightbox_active = getAllMrkLightBox.getAttribute('data-enable-mrk-lightbox');

        let unique_id = getAllMrkLightBox.getAttribute('data-enable-mrk-lightbox-unique-id') || '';

        let is_thumbnails_active = getAllMrkLightBox.getAttribute('data-mrk-lightbox-thumbnails');
        let img_alt = getAllMrkLightBox.getAttribute('data-mrk-lightbox-img-alt')

        // stop running when mrk lightbox is not active on this element
        if(!is_mrk_lightbox_active) return;

        let anchor_links = getAllMrkLightBox.querySelectorAll('a');

        //loop through the anchor links and set the data-fslightbox
        anchor_links.forEach((anchor_link, index) => {

            // if the anchor does not link to the image src, find the image src child of the anchor tag and set its value to the anchor href
            let image_el = anchor_link.querySelector('img');

            if(image_el) {
                let image_el_src = image_el.getAttribute('src') || '';

                anchor_link.setAttribute('data-thumb', image_el_src);

                //set the data alt
                if(img_alt) {
                    let image_el_alt = image_el.getAttribute('alt') || '';

                    anchor_link.setAttribute('data-alt', image_el_alt);
                }
            }

            anchor_link.setAttribute('data-mrklightbox', unique_id);
        })
    } )
}