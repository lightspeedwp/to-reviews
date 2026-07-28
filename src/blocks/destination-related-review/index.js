/**
 * Destination Related Review Block Variation
 *
 * Registers a block variation for displaying destination related to the current review.
 * Only available on review post type edit screens.
 *
 * @since 2.1.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerDestinationRelatedReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/destination-related-review',
            title: __('Related Destination', 'to-reviews'),
            icon: 'star-filled',
            description: __('Display destination related to this review.', 'to-reviews'),
            category: 'lsx-tour-operator',
            keywords: [
                __('review', 'to-reviews'),
                __('destination', 'to-reviews'),
                __('related', 'to-reviews'),
                __('query', 'to-reviews'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Destination', 'to-reviews'),
                },
                className: 'lsx-destination-related-review-query-wrapper',
                align: 'full',
                backgroundColor: 'primary-200',
                layout: {
                    type: 'constrained',
                },
                tagName: 'section',
            },
            innerBlocks: [
                [
                    'core/group',
                    {
                        align: 'wide',
                        layout: { type: 'flex', flexWrap: 'nowrap' },
                    },
                    [
                        [
                            'core/separator',
                            { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                        ],
                        [
                            'core/heading',
                            {
                                textAlign: 'center',
                                content: __('Related Destination', 'to-reviews'),
                                level: 2,
                            },
                        ],
                        [
                            'core/separator',
                            { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                        ],
                    ],
                ],
                [
                    'core/group',
                    { align: 'wide', layout: { type: 'constrained' } },
                    [
                        [
                            'core/query',
                            {
                                metadata: {
                                    name: __('Related destination query', 'to-reviews'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'destination',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-destination-related-review-query',
                                        layout: { type: 'grid', columnCount: 3 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/destination-card' },
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/group',
                        attributes: {
                            align: 'wide',
                            layout: { type: 'flex', flexWrap: 'nowrap' },
                        },
                        innerBlocks: [
                            {
                                name: 'core/separator',
                                attributes: { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                            },
                            {
                                name: 'core/heading',
                                attributes: {
                                    textAlign: 'center',
                                    content: __('Related Destination', 'to-reviews'),
                                    level: 2,
                                },
                            },
                            {
                                name: 'core/separator',
                                attributes: { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                            },
                        ],
                    },
                    {
                        name: 'core/group',
                        attributes: { align: 'wide', layout: { type: 'constrained' } },
                        innerBlocks: [
                            {
                                name: 'core/group',
                                attributes: {
                                    className: 'lsx-destination-related-review-query',
                                    layout: { type: 'grid', columnCount: 3 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-destination-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('South Africa', 'to-reviews'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('Home to breathtaking landscapes, diverse wildlife, and a rich cultural heritage.', 'to-reviews') } },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-destination-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Zimbabwe', 'to-reviews'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('A destination known for its natural wonders and unforgettable wildlife adventures.', 'to-reviews') } },
                                        ],
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['review'],
        ['review'],
        registerDestinationRelatedReviewVariation
    );
    conditionalRegister();
});
