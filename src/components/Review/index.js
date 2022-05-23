import { __ } from '@wordpress/i18n';
import StarRating from '../StarRating';
import BlankAvatar from '../../images/blank-avatar.png';
import ShowMoreText from 'react-show-more-text';

export default function Review( { index, review = [], reviewLines } ) {
    console.log( reviewLines );
    return (
        <div className={'rbg-business-review'} key={index}>
            <div className={'rbg-business-review-google-icon'} />
            <div className={'rbg-business-review-user'}>
                <div className={'rbg-business-review-user-image'}>
                    {review.profile_photo_url && (
                        <img src={review.profile_photo_url} alt={review.author_name} />
                    )}
                    {!review.profile_photo_url && (
                        <img src={BlankAvatar} alt={review.author_name} />
                    )}

                </div>
                <div className={'rbg-business-review-user-name'}>
                    {review.author_name}
                </div>
            </div>
            <div className={'rbg-business-review-content'}>
                <div className={'rbg-business-review-content-rating'}>
                    <StarRating
                        overallRating={review.rating}
                        date={review.relative_time_description}
                    />
                </div>
                <div className={'rbg-business-review-content-text'}>
                    <ShowMoreText
                        lines={reviewLines}
                        more={__( 'Read more', 'review-block-google-places' )}
                        less={__( 'Read less', 'review-block-google-places' )}
                    >{review.text}</ShowMoreText>
                </div>
            </div>
        </div>
    );

}
