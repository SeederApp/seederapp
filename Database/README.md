##How to remotely connect to AppFog


1. Install MySQL Workbench http://www.mysql.com/products/workbench/

2. Follow the instructions here https://docs.appfog.com/getting-started/af-cli#af-cli-install-windows making sure you install Ruby 1.9.3

3. Open a Git Bash or CMD Prompt with Ruby and type 
<pre><code>gem install caldecott</code></pre>

4. Login with the seederapp credentials
<pre><code>af login</pre></code>

5. Connect to the database and chose "none"
<pre><code>af tunnel seeder</code></pre>

6. Make note of the username and password and use them in your app
