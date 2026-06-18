/**
 * Review Date of Visit Block Variation
 *
 * Registers a block variation for displaying the date of visit.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewDateOfVisitVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-date-of-visit',
            title: __('Review Date of Visit', 'to-reviews'),
            icon: 'calendar',
            category: 'lsx-tour-operator',
            description: __('Displays the date of visit for this review.', 'to-reviews'),
            keywords: [
                __('date', 'to-reviews'),
                __('visit', 'to-reviews'),
                __('review', 'to-reviews'),
                __('travel', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Review Date of Visit', 'to-reviews'),
                },
                className: 'lsx-review-date-of-visit-wrapper',
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
                        layout: { type: 'flex', flexWrap: 'nowrap' },
                    },
                    [
                        [
                            'lsx-tour-operator/icons',
                            {
                                iconType: 'solid',
                                iconName: 'calendarIcon',
                            },
                        ],
                    ],
                ],
                [
                    'core/group',
                    {
                        layout: { type: 'flex', orientation: 'vertical', flexWrap: 'nowrap' },
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
                                                key: 'date_of_visit_start',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Visit From:', 'to-reviews'),
                                prefixBold: true,
                            },
                        ],
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: {
                                            source: 'lsx/post-meta',
                                            args: {
                                                key: 'date_of_visit_end',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Visit To:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'calendarIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Visit From: ', 'to-reviews') + '</strong>' + __('January 2024', 'to-reviews'),
                                },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Visit To: ', 'to-reviews') + '</strong>' + __('February 2024', 'to-reviews'),
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
        registerReviewDateOfVisitVariation
    );

    conditionalRegister();
});
