<div style="background-color: #f4f4f4; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc;">
    <strong>Concert App:</strong> 
    User: <?php echo htmlspecialchars($_SESSION['person']); ?> | 
    Date: <?php echo htmlspecialchars($_SESSION['res_date']); ?> | 
    City: <?php echo htmlspecialchars($_SESSION['res_city']); ?>
    <br><br>
    
    <a href="flights.php" style="margin-right: 15px;">Flights</a>
    <a href="hotels.php" style="margin-right: 15px;">Hotels</a>
    
    <form action="cancel_action.php" method="POST" style="display:inline;" onsubmit="return confirm('Cancel all reservations made in this session?');">
        <button type="submit" style="color: red;">Cancel All Reservations</button>
    </form>
    <a href="logout.php" style="float: right; color: #555;">End Session (Logout)</a>
    <div style="clear: both;"></div>
</div>