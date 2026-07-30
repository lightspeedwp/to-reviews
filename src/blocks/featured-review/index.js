/**
 * Featured Review Block Variation
 *
 * Registers a block variation for displaying featured reviews.
 * Available across all post types and templates.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';

wp.domReady(() => {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/featured-review',
        title: __('Featured Reviews', 'to-reviews'),
        icon: 'star-filled',
        description: __('Displays Reviews with the Featured tag.', 'to-reviews'),
        category: 'lsx-tour-operator',
        keywords: [
            __('featured', 'to-reviews'),
            __('reviews', 'to-reviews'),
            __('testimonials', 'to-reviews'),
        ],
        attributes: {
            metadata: {
                name: 'Featured Review',
            },
            className: 'lsx-featured-review-query-wrapper',
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
                        {
                            style: {
                                layout: { selfStretch: 'fill', flexSize: null },
                            },
                        },
                    ],
                    [
                        'core/heading',
                        {
                            textAlign: 'center',
                            content: __('Featured Reviews', 'to-reviews'),
                        },
                    ],
                    [
                        'core/separator',
                        {
                            style: {
                                layout: { selfStretch: 'fill', flexSize: null },
                            },
                        },
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
                                name: 'Featured Review Query',
                            },
                            query: {
                                perPage: 8,
                                postType: 'review',
                                order: 'asc',
                                orderBy: 'date',
                            },
                            align: 'wide',
                        },
                        [
                            [
                                'core/post-template',
                                {
                                    className: 'lsx-featured-review-query',
                                    layout: {
                                        type: 'grid',
                                        columnCount: 3,
                                    },
                                },
                                [
                                    [
                                        'core/pattern',
                                        {
                                            slug: 'lsx-tour-operator/review-card',
                                        },
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        isActive: (blockAttributes, variationAttributes) => {
            return blockAttributes.className === variationAttributes.className;
        },
    });
});
