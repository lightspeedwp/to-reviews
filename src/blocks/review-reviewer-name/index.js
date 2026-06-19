/**
 * Review Reviewer Name Block Variation
 *
 * Registers a block variation for displaying the reviewer name.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Reviews
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewReviewerNameVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-reviewer-name',
            title: __('Reviewer Name', 'to-reviews'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Displays the reviewer name for this review.', 'to-reviews'),
            keywords: [
                __('reviewer', 'to-reviews'),
                __('name', 'to-reviews'),
                __('author', 'to-reviews'),
                __('review', 'to-reviews'),
            ],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: {
                    name: __('Reviewer Name', 'to-reviews'),
                },
                className: 'lsx-reviewer-name-wrapper',
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
                                iconName: 'reviewerIcon',
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
                                                key: 'reviewer_name',
                                            },
                                        },
                                    },
                                },
                                prefix: __('Reviewer:', 'to-reviews'),
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
                                attributes: { iconType: 'solid', iconName: 'reviewerIcon' },
                            },
                            {
                                name: 'core/paragraph',
                                attributes: {
                                    content: '<strong>' + __('Reviewer: ', 'to-reviews') + '</strong>' + __('Jane Smith', 'to-reviews'),
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
        registerReviewReviewerNameVariation
    );

    conditionalRegister();
});
