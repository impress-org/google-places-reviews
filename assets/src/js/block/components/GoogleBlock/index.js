import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { dispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import { Icon, Spinner } from '@wordpress/components';

const GoogleBlock = (props) => {
    return (
        <div id={`rbg-wrap`} className={`rbg-wrap`}>
            Hello123
        </div>
    );
};

GoogleBlock.defaultProps = {
    attributes: [],
};
export default GoogleBlock;
