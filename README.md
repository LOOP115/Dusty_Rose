# Dusty Rose



### [Specification](https://github.com/LOOP115/Dusty_Rose/blob/main/resources/ass1-spec.pdf)



### Testing Scenario (75%)

You will be testing the full web application specified in the “Scope” section, and are expected to find the following vulnerabilities:

* LFI -> FLAG{} is available
* SQL Injection -> FLAG{} is available
* XSS -> FLAG{} is available
* Information Disclosure -> FLAG{} is available

Please ensure you write up these findings in a suitable format in your report as you find them.

**Also make sure to add in your own mitigation recommendations! The practicality of the remediation is very important (tailor the recommendations to the application).**

**BONUS MARKS:** If you are able to identify vulnerabilities that have not been listed, please report them for a chance at bonus marks. Bonus marks will be provided at the discretion of the lecturer based on complexity of the finding and quality of the writeup.

#### Scope

* Testing must only be performed on http://assignment-dusty-rose.unimelb.life/
* Testing must be manual only. Manual tools may be used (Burp, Zap, etc), however you may not use the automated scanning capabilities of these tools.
* No automated scanning or automated tools can be used.
* No load testing, denial of service (DOS) or distributed denial of service (DDOS) attacks. You may use Burp’s Intruder, but use less than 30 payloads per minute.



### Report (25%)

For this assignment, we expect a professionally written report, provided to the client (teaching staff), explaining and specifying each vulnerability you identified by

* discussing the vulnerability,
* the process of exploitation (steps to reproduce the exploits),
* the potential impact to the organisation,
* and the remediation (making sure to tailor it to the application).

**Also, please ensure that the flag is displayed in a screenshot at the end of each challenge’s writeup. We will not be accepting any flags that are not displayed in a screenshot.**



### Submission

* All students must submit a single zip file with all their code and a separate PDF version of their report (PDF not in the zip).
* The zip must be named <username>-assignment1.zip (e.g. testuser1 assignment1.zip).

* All code for each challenge must be clearly labelled and stored in a separate file, so it is not confused with the code for other challenges.
* Finally, all code must be referenced within the report. This implies that there will be code in both the report and the separate code file for each task.

