/**
 * Destination to Review Block Variation
 *
 * Registers a block variation for displaying destinations connected to this review.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerDestinationToReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/destination-to-review',
            title: __('Destination to Review', 'to-reviews'),
            icon: 'admin-site',
            category: 'lsx-tour-operator',
            description: __('Displays the destinations connected to this review.', 'to-reviews'),
            keywords: [
                __('destination', 'to-reviews'),
                __('review', 'to-reviews'),
                __('connection', 'to-reviews'),
                __('location', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Destination to Review', 'to-reviews'),
                },
                className: 'lsx-destination-to-review-wrapper',
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
                                iconName: 'destinationIcon',
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
                                                key: 'destination_to_review',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Destination:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'destinationIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Destination: ', 'to-reviews') + '</strong>' + __('Cape Town, South Africa', 'to-reviews'),
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
        registerDestinationToReviewVariation
    );

    conditionalRegister();
});
