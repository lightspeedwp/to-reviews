/**
 * Review Number of Adults Block Variation
 *
 * Registers a block variation for displaying the number of adults.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewNoAdultsVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-no-adults',
            title: __('Review Number of Adults', 'to-reviews'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Displays the number of adults on this review.', 'to-reviews'),
            keywords: [
                __('adults', 'to-reviews'),
                __('guests', 'to-reviews'),
                __('review', 'to-reviews'),
                __('pax', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Review Number of Adults', 'to-reviews'),
                },
                className: 'lsx-no-adults-wrapper',
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
                                iconName: 'adultsIcon',
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
                                            source: 'lsx/post-meta',
                                            args: {
                                                key: 'no_adults',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Adults:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'adultsIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Adults: ', 'to-reviews') + '</strong>' + '2',
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
        registerReviewNoAdultsVariation
    );

    conditionalRegister();
});
