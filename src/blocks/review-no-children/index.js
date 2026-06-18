/**
 * Review Number of Children Block Variation
 *
 * Registers a block variation for displaying the number of children.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewNoChildrenVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-no-children',
            title: __('Review Number of Children', 'to-reviews'),
            icon: 'groups',
            category: 'lsx-tour-operator',
            description: __('Displays the number of children on this review.', 'to-reviews'),
            keywords: [
                __('children', 'to-reviews'),
                __('kids', 'to-reviews'),
                __('review', 'to-reviews'),
                __('pax', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Review Number of Children', 'to-reviews'),
                },
                className: 'lsx-review-no-children-wrapper',
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
                                iconName: 'childrenIcon',
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
                                                key: 'no_children',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Children:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'childrenIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Children: ', 'to-reviews') + '</strong>' + '0',
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
        registerReviewNoChildrenVariation
    );

    conditionalRegister();
});
