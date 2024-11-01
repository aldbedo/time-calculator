import csv
import os
from datetime import datetime, timedelta

# Function to calculate working time during office hours
def calculate_working_time(reported_date, acknowledged_date):
    office_start = 7
    office_end = 17
    total_working_minutes = 0

    current_date = reported_date
    while current_date < acknowledged_date:
        if current_date.weekday() < 5:  # Monday to Friday
            start_of_day = current_date.replace(hour=office_start, minute=0, second=0)
            end_of_day = current_date.replace(hour=office_end, minute=0, second=0)

            effective_start = max(start_of_day, reported_date) if current_date.date() == reported_date.date() else start_of_day
            effective_end = min(end_of_day, acknowledged_date) if current_date.date() == acknowledged_date.date() else end_of_day

            if effective_start < effective_end:
                total_working_minutes += (effective_end - effective_start).total_seconds() / 60

        current_date += timedelta(days=1)

    # Convert total working minutes to seconds
    total_seconds = total_working_minutes * 60
    days = total_seconds // 86400
    hours = (total_seconds % 86400) // 3600
    minutes = (total_seconds % 3600) // 60
    seconds = total_seconds % 60

    return int(days), int(hours), int(minutes), int(seconds)

# Read from CSV and write results
input_file = 'D:/Programs/xampp/htdocs/website/tmp/upload/dates.csv'  # Change this to your input CSV file path
output_file = 'D:/Programs/xampp/htdocs/website/tmp/output/output.csv'  # Change this to your output CSV file path

try:
    if not os.path.exists(input_file):
        raise FileNotFoundError(f"Input file not found: {input_file}")

    with open(input_file, mode='r', newline='', encoding='utf-8') as csvfile:
        reader = csv.DictReader(csvfile)
        rows = []

        for row in reader:
            # Parse dates from the CSV
            reported_date = datetime.strptime(row['date reported'], "%m/%d/%Y %H:%M")
            acknowledged_date = datetime.strptime(row['date acknowledge'], "%m/%d/%Y %H:%M")

            # Calculate working time
            days, hours, minutes, seconds = calculate_working_time(reported_date, acknowledged_date)

            # Append the results to the row
            row['day'] = days
            row['hour'] = hours
            row['minute'] = minutes
            row['second'] = seconds
            rows.append(row)

        # Ensure output directory exists
        os.makedirs(os.path.dirname(output_file), exist_ok=True)

    # Write back to the same CSV file with additional columns
    with open(output_file, mode='w', newline='', encoding='utf-8') as csvfile:
        fieldnames = ['date reported', 'date acknowledge', 'day', 'hour', 'minute', 'second']
        writer = csv.DictWriter(csvfile, fieldnames=fieldnames)

        writer.writeheader()  # Write the header
        writer.writerows(rows)  # Write the rows with calculated data

    print("Processing complete. The output has been written back to the CSV file.")

except UnicodeDecodeError:
    print("Error: Unable to decode the CSV file. Please check the file encoding.")
except Exception as e:
    print(f"An error occurred: {e}")
