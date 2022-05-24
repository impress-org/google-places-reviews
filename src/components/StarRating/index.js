import { __ } from '@wordpress/i18n';

export default function StarRating( { overallRating, totalRatings = '', date = '', size = 'small' } ) {

    const starClass = ( overallRating ) => {
        const rating = overallRating.toString().split( '.' );
        if ( undefined !== rating[1 ] && rating[1] >= 5 ) {
            return rating[0] + '-half';
        }
        return rating[0]
    };

    return (
        <div className={`rbg-business-stars-wrap rbg-business-stars-wrap-${size}`}>
            <span
                aria-label={`${overallRating} out of 5 stars`}
                className={`rbg-business-stars rbg-business-stars--${starClass( overallRating )}  rbg-business-stars-${size}`}></span>
            {totalRatings && (
                <span
                    className={'rbg-business-stars-reviews'}>{totalRatings} {__( 'reviews', 'google-places-reviews' )}</span>
            )}
            {date && (
                <span className={'rbg-business-stars-date'}>{date}</span>
            )}

        </div>
    );
}
