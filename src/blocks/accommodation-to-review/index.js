/**
 * Accommodation to Review Block Variation
 *
 * Registers a block variation for displaying accommodations connected to this review.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerAccommodationToReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/accommodation-to-review',
            title: __('Accommodation to Review', 'to-reviews'),
            icon: 'admin-home',
            category: 'lsx-tour-operator',
            description: __('Displays the accommodations connected to this review.', 'to-reviews'),
            keywords: [
                __('accommodation', 'to-reviews'),
                __('review', 'to-reviews'),
                __('connection', 'to-reviews'),
                __('lodging', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Accommodation to Review', 'to-reviews'),
                },
                className: 'lsx-accommodation-to-review-wrapper',
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
                                iconName: 'accommodationIcon',
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
                                                key: 'accommodation_to_review',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Accommodation:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'accommodationIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Accommodation: ', 'to-reviews') + '</strong>' + __('Serengeti Safari Lodge', 'to-reviews'),
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
        registerAccommodationToReviewVariation
    );

    conditionalRegister();
});
