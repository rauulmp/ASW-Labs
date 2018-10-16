package asw01cs;


import org.apache.http.client.fluent.Form;
import org.apache.http.client.fluent.Request;
//This code uses the Fluent API

public class SimpleFluentClient {

	private static String URI = "http://localhost:8082/waslab01_ss/";

	public final static void main(String[] args) throws Exception {
    	
    	/* Insert code for Task #4 here */
	
		String lastid = Request.Post(URI)
	    .bodyForm(Form.form().add("author", "Carlitos").add("tweet_text",  "omegalul").build())
	    .addHeader("Accept","text/plain").execute().returnContent().asString();
		
		System.out.println(lastid);
    	System.out.println(Request.Get(URI).addHeader("Accept","text/plain").execute().returnContent());
    	
    	/* Insert code for Task #5 here */
    	
    	Request.Post(URI).addHeader("Accept","Esborrar").bodyForm(Form.form().add("LastTw",  lastid).build()).execute();
    	System.out.println(Request.Get(URI).addHeader("Accept","text/plain").execute().returnContent());
    }
}

