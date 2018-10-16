package twitter;

import java.util.Date;

import twitter4j.FilterQuery;
import twitter4j.Query;
import twitter4j.QueryResult;
import twitter4j.StallWarning;
import twitter4j.Status;
import twitter4j.StatusDeletionNotice;
import twitter4j.StatusListener;
import twitter4j.Twitter;
import twitter4j.TwitterFactory;
import twitter4j.TwitterStream;
import twitter4j.TwitterStreamFactory;

public class SimpleClient {

	public static void main(String[] args) throws Exception {
		
		/* final Twitter twitter = new TwitterFactory().getInstance();
		
		Query q = new Query();
		q.setQuery("@fib_was");
		q.setResultType(Query.RECENT);
		q.setCount(1); //1 per pagina
		
		QueryResult result = twitter.search(q);
		
		Status TweetFibWas = result.getTweets().get(0);
		
		//mostra per pantalla el darrer tweet
		System.out.println(TweetFibWas.getText());
		
		//fem retweet
		twitter.retweetStatus(TweetFibWas.getId());
		*/
		
		TwitterStream twitterStream = new TwitterStreamFactory().getInstance();
		StatusListener statusListener = new StatusListener() {

			@Override
			public void onException(Exception arg0) {
				// TODO Auto-generated method stub
				
			}

			@Override
			public void onDeletionNotice(StatusDeletionNotice arg0) {
				// TODO Auto-generated method stub
				
			}

			@Override
			public void onScrubGeo(long arg0, long arg1) {
				// TODO Auto-generated method stub
				
			}

			@Override
			public void onStallWarning(StallWarning arg0) {
				// TODO Auto-generated method stub
				
			}

			@Override
			public void onStatus(Status arg0) {
				// TODO Auto-generated method stub
				System.out.println(arg0.getUser().getName() + " (@" + arg0.getUser().getScreenName() + "): " + arg0.getText());
			}

			@Override
			public void onTrackLimitationNotice(int arg0) {
				// TODO Auto-generated method stub
				
			}
		};
        twitterStream.addListener(statusListener);
        FilterQuery fq = new FilterQuery();
		fq.track("#barcelona");
        twitterStream.filter(fq); 
	}
}
