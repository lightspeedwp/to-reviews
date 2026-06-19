/**
 * Review Rating Block Variation
 *
 * Registers a block variation for displaying the review rating.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewRatingVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-rating',
            title: __('Review Rating', 'to-reviews'),
            icon: 'star-filled',
            category: 'lsx-tour-operator',
            description: __('Displays the rating for this review.', 'to-reviews'),
            keywords: [
                __('rating', 'to-reviews'),
                __('review', 'to-reviews'),
                __('stars', 'to-reviews'),
                __('score', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Review Rating', 'to-reviews'),
                },
                className: 'lsx-rating-wrapper',
                layout: {
                    type: 'flex',
                    flexWrap: 'nowrap',
                    verticalAlignment: 'top',
                },
            },
            innerBlocks: [
                [
                    'core/group',
                    {
                        layout: {
                            type: 'flex',
                            flexWrap: 'nowrap',
                        },
                    },
                    [
                        [
                            'lsx-tour-operator/icons',
                            {
                                iconType: 'solid',
                                iconName: 'ratingIcon',
                            },
                        ],
                    ],
                ],
                [
                    'core/group',
                    {
                        layout: {
                            type: 'flex',
                            flexWrap: 'nowrap',
                        },
                    },
                    [
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: {
                                            source: 'lsx/post-meta',
                                            args: {
                                                key: 'rating',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Rating:', 'to-reviews'),
                                prefixBold: true,
                            },
                        ],
                    ],
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/group',
                        attributes: {
                            layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' },
                        },
                        innerBlocks: [
                            {
                                name: 'lsx-tour-operator/icons',
                                attributes: { iconType: 'solid', iconName: 'ratingIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Rating: ', 'to-reviews') + '</strong>' + '5',
                                },
                            },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['review'],
        ['review'],
        registerReviewRatingVariation
    );

    conditionalRegister();
});
