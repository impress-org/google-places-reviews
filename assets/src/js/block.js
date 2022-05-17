import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';
import Edit from './block/edit';
import GoogleBlock from './block/components/GoogleBlock';

registerBlockType('google-places-reviews/reviews', {
    title: __('Reviews Block for Google', 'google-places-reviews'),

    edit: Edit,
    save: () => {
        return null;
    },
});

domReady(function () {
    // Don't run when Gutenberg / Block editor is active.
    if (document.body.classList.contains('block-editor-page')) {
        return;
    }

    const reviewBlocks = document.querySelectorAll('.root-google-reviews-block');

    reviewBlocks.forEach((reviewBlock) => {
        const attributes = reviewBlock.dataset;
        render(<GoogleBlock attributes={attributes} />, reviewBlock);
    });
});
