/**
 * Review Related Review Block Variation
 *
 * Registers a block variation for displaying other reviews.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';
import { __ } from '@wordpress/i18n';

function registerReviewRelatedReviewVariation() {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/review-related-review',
        title: __('Related Reviews', 'to-reviews'),
        icon: 'star-filled',
        description: __('Displays other reviews from the site.', 'to-reviews'),
        category: 'lsx-tour-operator',
        keywords: [
            __('reviews', 'to-reviews'),
            __('related', 'to-reviews'),
            __('similar', 'to-reviews'),
            __('testimonials', 'to-reviews'),
        ],
        attributes: {
            metadata: {
                name: __('Related Reviews', 'to-reviews'),
            },
            className: 'lsx-review-related-review-query-wrapper',
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
                            content: __('Related Reviews', 'to-reviews'),
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
                                name: __('Related Review Query', 'to-reviews'),
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
                                    className: 'lsx-review-related-review-query',
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
        isActive: (blockAttributes) => {
            return (
                blockAttributes.className === 'lsx-review-related-review-query-wrapper' ||
                (blockAttributes.className &&
                    blockAttributes.className.includes('lsx-review-related-review-query-wrapper'))
            );
        },
    });
}

const conditionalRegister = registerForPostTypesAndTemplates(
    ['review'],
    ['review'],
    registerReviewRelatedReviewVariation
);

wp.domReady(conditionalRegister);
