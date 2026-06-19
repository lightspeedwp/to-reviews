/**
 * Review to Tour Block Variation
 *
 * Registers a block variation for displaying tours connected to this review.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewToTourVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-to-tour',
            title: __('Review to Tour', 'to-reviews'),
            icon: 'location-alt',
            category: 'lsx-tour-operator',
            description: __('Displays the tours connected to this review.', 'to-reviews'),
            keywords: [
                __('tour', 'to-reviews'),
                __('review', 'to-reviews'),
                __('connection', 'to-reviews'),
                __('itinerary', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Review to Tour', 'to-reviews'),
                },
                className: 'lsx-to-tour-wrapper',
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
                        layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' },
                    },
                    [
                        [
                            'lsx-tour-operator/icons',
                            {
                                iconType: 'solid',
                                iconName: 'tourIcon',
                            },
                        ],
                    ],
                ],
                [
                    'core/group',
                    {
                        layout: { type: 'flex', flexWrap: 'nowrap' },
                    },
                    [
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: {
                                            source: 'lsx/post-connection',
                                            args: {
                                                key: 'tour_to_review',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Tour:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'tourIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Tour: ', 'to-reviews') + '</strong>' + __('Big Five Safari', 'to-reviews'),
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
        registerReviewToTourVariation
    );

    conditionalRegister();
});
