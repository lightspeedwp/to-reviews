/**
 * Review Gallery Block Variation
 *
 * Registers a gallery block variation scoped to the review post type.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewGalleryVariation = () => {
        wp.blocks.registerBlockVariation('core/gallery', {
            name: 'lsx-tour-operator/review-gallery',
            title: __('Review Gallery', 'to-reviews'),
            icon: 'format-gallery',
            category: 'lsx-tour-operator',
            description: __('Display the gallery images for this review.', 'to-reviews'),
            keywords: [
                __('gallery', 'to-reviews'),
                __('images', 'to-reviews'),
                __('review', 'to-reviews'),
                __('photos', 'to-reviews'),
            ],
            attributes: {
                metadata: {
                    name: __('Review Gallery', 'to-reviews'),
                    bindings: {
                        content: {
                            source: 'lsx/gallery',
                        },
                    },
                },
                linkTo: 'none',
                sizeSlug: 'thumbnail',
            },
            innerBlocks: [
                [
                    'core/image',
                    {
                        sizeSlug: 'large',
                        url: lsxToEditor.assetsUrl + 'blocks/placeholder.png',
                    },
                ],
                [
                    'core/image',
                    {
                        sizeSlug: 'large',
                        url: lsxToEditor.assetsUrl + 'blocks/placeholder.png',
                    },
                ],
                [
                    'core/image',
                    {
                        sizeSlug: 'large',
                        url: lsxToEditor.assetsUrl + 'blocks/placeholder.png',
                    },
                ],
            ],
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['review'],
        ['review'],
        registerReviewGalleryVariation
    );

    conditionalRegister();
});
