/**
 * Tour Related Review Block Variation
 *
 * Registers a block variation for displaying tour related to the current review.
 * Only available on review post type edit screens.
 *
 * @since 2.1.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTourRelatedReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/tour-related-review',
            title: __('Related Tour', 'to-reviews'),
            icon: 'star-filled',
            description: __('Display tour related to this review.', 'to-reviews'),
            category: 'lsx-tour-operator',
            keywords: [
                __('review', 'to-reviews'),
                __('tour', 'to-reviews'),
                __('related', 'to-reviews'),
                __('query', 'to-reviews'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Tour', 'to-reviews'),
                },
                className: 'lsx-tour-related-review-query-wrapper',
                align: 'full',
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
                                content: __('Related Tour', 'to-reviews'),
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
                                    name: __('Related tour query', 'to-reviews'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'tour',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-tour-related-review-query',
                                        layout: { type: 'grid', columnCount: 2 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/tour-card' },
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
                                    content: __('Related Tour', 'to-reviews'),
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
                                    className: 'lsx-tour-related-review-query',
                                    layout: { type: 'grid', columnCount: 2 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-tour-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('African Safari Adventure', 'to-reviews'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('A guided multi-day safari through some of Africa’s most iconic wildlife reserves.', 'to-reviews') } },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-tour-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Coastal Beach Escape', 'to-reviews'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('A relaxing tour along the coastline, combining beach time with local sightseeing.', 'to-reviews') } },
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
        registerTourRelatedReviewVariation
    );
    conditionalRegister();
});
