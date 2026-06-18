/**
 * Review Related Tour Block Variation
 *
 * Registers a block variation for displaying reviews related to the current tour.
 * Only available on tour post type edit screens.
 *
 * @since 2.1.0
 * @package TO_Reviews
 */

import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';
import { __ } from '@wordpress/i18n';

/**
 * Register the review related tour block variation
 */
function registerReviewRelatedTourVariation() {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/review-related-tour',
        title: __('Related Reviews', 'to-reviews'),
        icon: 'star-filled',
        description: __('Displays reviews related to this tour.', 'to-reviews'),
        category: 'lsx-tour-operator',
        keywords: [
            __('reviews', 'to-reviews'),
            __('tour', 'to-reviews'),
            __('related', 'to-reviews'),
            __('testimonials', 'to-reviews'),
        ],
        attributes: {
            metadata: {
                name: __('Related Reviews', 'to-reviews'),
            },
            className: 'lsx-review-related-tour-query-wrapper',
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
                            content: __('Reviews', 'to-reviews'),
                            level: 2,
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
                                name: __('Related Reviews Query - Tour', 'to-reviews'),
                            },
                            query: {
                                perPage: 8,
                                postType: 'review',
                                order: 'desc',
                                orderBy: 'date',
                            },
                            align: 'wide',
                        },
                        [
                            [
                                'core/post-template',
                                {
                                    className: 'lsx-review-related-tour-query',
                                    layout: {
                                        type: 'grid',
                                        columnCount: 2,
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
                            attributes: {
                                style: {
                                    layout: { selfStretch: 'fill', flexSize: null },
                                },
                            },
                        },
                        {
                            name: 'core/heading',
                            attributes: {
                                textAlign: 'center',
                                content: __('Reviews', 'to-reviews'),
                                level: 2,
                            },
                        },
                        {
                            name: 'core/separator',
                            attributes: {
                                style: {
                                    layout: { selfStretch: 'fill', flexSize: null },
                                },
                            },
                        },
                    ],
                },
                {
                    name: 'core/group',
                    attributes: {
                        align: 'wide',
                        layout: { type: 'constrained' },
                    },
                    innerBlocks: [
                        {
                            name: 'core/group',
                            attributes: {
                                className: 'lsx-review-related-tour-query',
                                layout: {
                                    type: 'grid',
                                    columnCount: 2,
                                },
                            },
                            innerBlocks: [
                                {
                                    name: 'core/group',
                                    attributes: {
                                        className: 'lsx-review-card',
                                        style: {
                                            border: {
                                                width: '1px',
                                                style: 'solid',
                                                color: '#e2e8f0',
                                            },
                                            spacing: {
                                                padding: '1.5rem',
                                            },
                                        },
                                    },
                                    innerBlocks: [
                                        {
                                            name: 'core/heading',
                                            attributes: {
                                                content: __('Amazing Safari Experience', 'to-reviews'),
                                                level: 3,
                                            },
                                        },
                                        {
                                            name: 'core/paragraph',
                                            attributes: {
                                                content: __('Our family had the most incredible time on the African safari. The guides were knowledgeable and the wildlife viewing was spectacular.', 'to-reviews'),
                                            },
                                        },
                                        {
                                            name: 'core/paragraph',
                                            attributes: {
                                                content: __('— Sarah Johnson', 'to-reviews'),
                                                style: {
                                                    typography: {
                                                        fontStyle: 'italic',
                                                    },
                                                },
                                            },
                                        },
                                    ],
                                },
                                {
                                    name: 'core/group',
                                    attributes: {
                                        className: 'lsx-review-card',
                                        style: {
                                            border: {
                                                width: '1px',
                                                style: 'solid',
                                                color: '#e2e8f0',
                                            },
                                            spacing: {
                                                padding: '1.5rem',
                                            },
                                        },
                                    },
                                    innerBlocks: [
                                        {
                                            name: 'core/heading',
                                            attributes: {
                                                content: __('Perfect Beach Getaway', 'to-reviews'),
                                                level: 3,
                                            },
                                        },
                                        {
                                            name: 'core/paragraph',
                                            attributes: {
                                                content: __('The resort was beautiful and the staff went above and beyond to make our vacation memorable. Highly recommended!', 'to-reviews'),
                                            },
                                        },
                                        {
                                            name: 'core/paragraph',
                                            attributes: {
                                                content: __('— Michael Chen', 'to-reviews'),
                                                style: {
                                                    typography: {
                                                        fontStyle: 'italic',
                                                    },
                                                },
                                            },
                                        },
                                    ],
                                },
                            ],
                        },
                    ],
                },
            ],
        },
        isActive: (blockAttributes) => {
            return (
                blockAttributes.className === 'lsx-review-related-tour-query-wrapper' ||
                (blockAttributes.className &&
                    blockAttributes.className.includes('lsx-review-related-tour-query-wrapper'))
            );
        },
    });
}

// Register conditionally for tour post types and tour templates
const conditionalRegister = registerForPostTypesAndTemplates(
    ['tour'],
    ['tour'],
    registerReviewRelatedTourVariation
);

wp.domReady(conditionalRegister);
