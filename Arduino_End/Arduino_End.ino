#include <Servo.h>

Servo myservo;
#define NUM_SAMPLES 100 //Num of samples (1 Hz)

int sum = 0;
unsigned char sample_count = 0;
float voltage = 0.0;

float approx_best_v = 0.0;
bool goUp = true;
int servo_pos = 90;

void setup()
{
    Serial.begin(9600);
    myservo.attach(9);//connect pin 9 with the control line(the middle line of Servo) 
    myservo.write(servo_pos);// move servos to center position -> 90°
}

float getVoltage(){

    sample_count = 0;
    sum = 0;
  
    while (sample_count < 10) {
        sum += analogRead(A2);
        sample_count++;
        delay(10);
    }

    float retval = ((float)sum / (float)10 * 5.015) / 1024.0;

    sample_count = 0;
    sum = 0;

    return retval * 11.123;
}

void loop()
{

    voltage = getVoltage();

    Serial.println(voltage);

    //Initiate manual search
    if(voltage > (approx_best_v + .04) || voltage < (approx_best_v-.04)){
      
        approx_best_v = voltage;
        int iters = 0;
        
        while(!((servo_pos + 5) > 135)){
            servo_pos = servo_pos + 5;
            myservo.write(servo_pos);
            delay(500);
            voltage = getVoltage();
            if(voltage > (approx_best_v + .05)) {
                approx_best_v = voltage;
                iters++;
            }else{
                servo_pos -= 5;
                myservo.write(servo_pos);
                break;
            }
        }
        
        if(iters == 0) {
            while(!((servo_pos - 5) < 45)){
                servo_pos = servo_pos - 5;
                myservo.write(servo_pos);
                delay(500);
                voltage = getVoltage();
                if(voltage > (approx_best_v + .05)) {
                    approx_best_v = voltage;
                }else{
                    servo_pos += 5;
                    myservo.write(servo_pos);
                    break;
                }
            }
        }

      approx_best_v = voltage;
    }
    
    sample_count = 0;
    sum = 0;
}
