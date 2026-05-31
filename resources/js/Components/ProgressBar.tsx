import { CircularProgressbar, buildStyles } from 'react-circular-progressbar';
import 'react-circular-progressbar/dist/styles.css';

interface ProgressBarProps {
    percentageUsed: number;
}

export default function ProgressBar({ percentageUsed }: ProgressBarProps) {

    return (
      <CircularProgressbar
        value={percentageUsed}
        text={`${percentageUsed}% Gastado`}
        styles={buildStyles({
          pathColor: '#3C0366',
            textColor: '#3C0366',
          trailColor: '#F5F5F5',
          textSize: '8',
        })}
      />
  )
}
