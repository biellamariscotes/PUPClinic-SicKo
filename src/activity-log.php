<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');

// Default sorting order
$sort_order = "ASC"; // Default to oldest to latest

// Check if user selected a sort option
if (isset($_GET['medReportsortCriteria'])) {
    switch ($_GET['medReportsortCriteria']) {
        case 'latest-oldest':
            $sort_order = "DESC"; // Latest to oldest
            break;
        case 'oldest-latest':
            $sort_order = "ASC"; // Oldest to latest
            break;
        default:
            $sort_order = "ASC"; // Default to oldest to latest
            break;
    }
}

// Fetch activity log records with correct sorting order
$query = "SELECT fullname, DATE_FORMAT(date, '%M %d, %Y') as formatted_date, DATE_FORMAT(date, '%h:%i %p') as formatted_time, action 
          FROM activity_log 
          ORDER BY date $sort_order";
$result = mysqli_query($conn, $query);

// Initialize array to store activities grouped by date
$activitiesByDate = [];

// Fetch and group activities by date
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['formatted_date'];
    $activitiesByDate[$date][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<style>
/*Activity LOg*/
.activitylog-body {
  display: flex;
  justify-content: center;
  margin: 1rem 1.25rem 2rem 1.25rem;
}

.activitylog-box {
  max-width: 100%;
  width: 76.313rem;
  height: 21.875rem;
  border-radius: 6px;
  background-color: #FCFCFC;
  box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;
}

.activitylog-date {
  font-family: 'Poppins', sans-serif;
  font-size: 1.563rem;
  font-weight: bold;
  color: black;
  text-align: left;
  margin: 1.875rem;
}

.activitylog-container {
  margin: 40px 40px;
  padding-top: 0.313rem;
  overflow-x: auto; /* Enable horizontal scrolling */
}

section {
  margin: 20px 0;
}
.timeline {
  margin-top: 35px;
  padding: 15px;
  display: grid;
  grid-template-columns: 40% auto;
  justify-content: center;
}

ul {
  margin-top: 30px;
  display: flex;
  flex-direction: row; /* Display items in a row */
  white-space: nowrap; /* Prevent wrapping of list items */
  list-style: none;
  padding: 0;
}

li {
  margin-top: 30px;
  position: relative;
  padding: 25px;
}
li p {
  padding: 0 20px;
  transition: 1.5s;
}

.date {
  position: absolute;
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  top: 30px;
  left: -100px;
  opacity: 0;
  transition: 1.5s;
  font-size: 12px;
}
.timeline-line {
  background: rgb(228, 228, 228);
  width: 4px;
  border-radius: 12px;
  position: relative;
  justify-self: end;
}

.timeline-point {
  border: none;
  position: absolute;
  border-radius: 50%;
  background: rgb(228, 228, 228);
  width: 8px;
  height: 8px;
  top: 30px;
  left: -38px;
  transition: 1.5s ease;
  cursor: pointer;
}
.timeline-innerline {
  position: absolute;
  background: #058789;
  width: 4px;
  height: 0%;

  top: 0%;
  left: 0%;

  transition: 1s linear;
}

@media screen and (min-width: 728px) {
  .timeline {
    display: inline-block;
  }
  ul {
    flex-direction: row;
  }
  li {
    margin-top: 0px;
    position: relative;
    width: 100%;
    padding: 0px;
  }
  li p {
    transform: translateY(-0.625rem);
    opacity: 0;
    /* padding: 0 20px; */
    transition: 1.5s;
  }

  .date {
    opacity: 0;
    transition: 1.5s;
    font-size: 12px;
    font-weight: 500;
    position: absolute;
    top: -60px;
    left: 30%;
  }

  .timeline-point {
    width: 15px;
    height: 15px;
    top: -2.5rem;
    left: 40%;
    transition: 1.5s ease;
    cursor: pointer;
  }

  .timeline-line {
    width: 100%;
    height: 4px;
  }

  .timeline-innerline {
    display: inline;
    position: absolute;
    background: #058789;
    width: 0%;
    height: 4px;

    top: 0%;
    left: 0%;

    transition: 1s linear;
    border-radius: 999px;
  }
}
</style>

<body>
  <div>

    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
        <?php
        include ('includes/sidebar/activity-log.php');
        ?>



    <div class="content" id="content" style="overflow-x: hidden;">
            <div class="med-reports-header" style="margin-bottom: 1.5rem;">
                <div class="med-reports-header-box">
                    <div class="medreports-header-text">
                        <span style="color: #E13F3D; font-size: 2rem;">Activity</span>
                        <span style="color: #058789; font-size: 2rem;">Log</span>
                    </div>
                    <div class="medreports-sorting-button" id="medReportsortingButton">
                    <form method="GET">
                    <select name="medReportsortCriteria" id="medReportsortCriteria" style="font-family: 'Poppins', sans-serif; font-weight: bold;" onchange="this.form.submit()">
                      <option value="" disabled selected hidden>Sort by</option>
                      <option value="latest-oldest" <?php if(isset($_GET['medReportsortCriteria']) && $_GET['medReportsortCriteria'] == 'latest-oldest') echo 'selected'; ?>>Latest to Oldest</option>
                      <option value="oldest-latest" <?php if(isset($_GET['medReportsortCriteria']) && $_GET['medReportsortCriteria'] == 'oldest-latest') echo 'selected'; ?>>Oldest to Latest</option>
                    </select>
                    </form>
                    </div>
                </div>
            </div>

            <?php foreach ($activitiesByDate as $date => $activities): ?>
            <div class="activitylog-body">
                <div class="activitylog-box">
                    <div class="activitylog-date"><?php echo $date; ?></div>
                    <div class="activitylog-container">
                        <section class="timeline">
                            <div class="timeline-line">
                                <span class="timeline-innerline"></span>
                            </div>
                            <ul>
                                <?php foreach ($activities as $activity): ?>
                                <li>
                                    <span class="timeline-point"></span>
                                    <span class="date"><?php echo $activity['formatted_time']; ?></span>
                                    <p>You <b style="font-weight: 600;"><?php echo $activity['action']; ?></b></p>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php include ('includes/footer.php'); ?>
        </div>
    </div>

<script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/script.js"></script>
<script src="scripts/loader.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const timelines = document.querySelectorAll(".timeline");

    timelines.forEach((timeline) => {
      const line = timeline.querySelector(".timeline-innerline");
      const timeline_events = timeline.querySelectorAll("ul li");

      function showTime(e) {
        e.setAttribute("done", "true");
        e.querySelector(".timeline-point").style.background = "#058789";
        e.querySelector(".date").style.opacity = "100%";
        e.querySelector("p").style.opacity = "100%";
        e.querySelector("p").style.transform = "translateY(0px)";
      }

      function hideTime(e) {
        e.removeAttribute("done");
        e.querySelector(".timeline-point").style.background = "rgb(228, 228, 228)";
        e.querySelector(".date").style.opacity = "0%";
        e.querySelector("p").style.opacity = "0%";
        e.querySelector("p").style.transform = "translateY(-10px)";
      }

      function slowLoop() {
        timeline_events.forEach((event, index) => {
          setTimeout(() => {
            showTime(event);
            timelineProgress(index + 1);
          }, index * 800);
        });
      }

      function timelineProgress(value) {
        let progress = `${(value / timeline_events.length) * 100}%`;
        if (window.matchMedia("(min-width: 728px)").matches) {
          line.style.width = progress;
        } else {
          line.style.height = progress;
        }
      }

      slowLoop();

      timeline_events.forEach((li, index) => {
        li.addEventListener("click", () => {
          if (li.getAttribute("done")) {
            timelineProgress(index);

            // hide all timeline events from last up to the point clicked
            timeline_events.forEach((ev, idx) => {
              if (idx >= index) {
                hideTime(ev);
              }
            });
          } else {
            timelineProgress(index + 1);
            // show all timeline events from first up to the point clicked
            timeline_events.forEach((ev, idx) => {
              if (idx <= index) {
                showTime(ev);
              }
            });
          }
        });
      });

      var doit;
      window.addEventListener("resize", () => {
        clearTimeout(doit);
        doit = setTimeout(resizeEnd, 1200);
      });

      function resizeEnd() {
        slowLoop();
      }
    });
  });
</script>
</body>

</html>