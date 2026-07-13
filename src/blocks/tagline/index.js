/**
 * Review Tagline Block Variation
 *
 * Registers a block variation for displaying the review tagline.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewTaglineVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-tagline',
            title: __('Review - Tagline', 'to-reviews'),
            icon: 'editor-textcolor',
            category: 'lsx-tour-operator',
            description: __('Displays the tagline for this review.', 'to-reviews'),
            keywords: [
                __('tagline', 'to-reviews'),
                __('review', 'to-reviews'),
                __('subtitle', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Review - Tagline', 'to-reviews'),
                },
                className: 'lsx-tagline-wrapper',
                layout: {
                    type: 'flex',
                    flexWrap: 'nowrap',
                    verticalAlignment: 'top',
                },
            },
            innerBlocks: [
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: {
                                    source: 'lsx/post-meta',
                                    args: {
                                        key: 'tagline',
                                    },
                                },
                            },
                        },
                        prefix: __('Tagline:', 'to-reviews'),
                        prefixBold: true,
                    },
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/paragraph',
                        attributes: {
                            content: '<strong>' + __('Tagline: ', 'to-reviews') + '</strong>' + __('A wonderful experience', 'to-reviews'),
                        },
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['review'],
        ['review'],
        registerReviewTaglineVariation
    );

    conditionalRegister();
});
