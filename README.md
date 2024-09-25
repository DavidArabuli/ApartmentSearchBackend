Apartment Search **Backend** repo is a **Server-side** part of a full-stack web app (check the frontend part nearby), that helps buyer to search for deals in Riga, Latvia real estate market.

While there are places to search for apartment ads in Riga, such as SS.lv, they require constant online presence and you must monitor the website manually.

Apartment Search Backend App serves all the necessary data for the frontend part of the App and creates API to consume:

-  periodically parses SS.lv RSS feed and extracts data from new ads
-  populates DB
-  creates API endpoints for filtering and selecting neccessary data like m2, price, districts etc.
-  arranges notification system, that automatically monitors new ads and notifies you (via email) when a desired apartment is being sold.
-  manages data for analytics of current market based on gathered ads.
-  creates Analytics API endpoints.
