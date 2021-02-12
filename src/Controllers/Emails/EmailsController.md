# Emails Controller 

You can do following things.

- #### Controller::createTemplate()
    Creating email Template

- #### Controller::postEmails()
    Sending Email

- #### Controller::getSentMails()
    get All Email that sent by Server

- #### Controller::getEmailTemplates()
    get All Email Templates

- #### Controller::getEmailTemplateByTitle()
    get single Email Template by Title, because Title should be uniquie

- #### Controller::editEmailtemplates()
    get All Email Templates

- #### Controller::deleteEmailTemplate()
    get All Email Templates


Below are some kind conditions in the e-commerce platforms that need to email their customer.
When :

- Member Register.
- Member need to remind their Password ( reseting password ).
- Member Password has been changed.
- Member making a new Transaction
- Login Info from different device from before / IP Address
- Payment - Pending Transaction
- Payment - Cancel Transaction 
- Payment - Success Transaction
- Order - Order on Processing / Packaging
- Order - Order on delivery
- Order - Order canceled
- Order - Order delivered
- Order - Order completed
- Remind their shopping cart if it is not empty (making sales Conversion)

The are others, but it depend on your business cases, each e-commerce has different implementations.

If you need some information how to do it by REST API. Please see in your Routings