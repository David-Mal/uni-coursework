#ifndef DOMAIN_H
#define DOMAIN_H

#include <string>

class HospitalDepartment {
protected:
    std::string hospitalName;
    int numberOfDoctors;
public:
    const std::string& getHospitalName() { return hospitalName; }
    virtual bool isEfficient() = 0;
    virtual std::string toString() = 0;
    virtual ~HospitalDepartment() = default;
};

class NeonatalUnit : public HospitalDepartment {

public:
    double averageGrade;
    NeonatalUnit(std::string hospitalName, int numberOfDoctors, double averageGrade);
    bool isEfficient() override;
    std::string toString() override;
};

class Surgery : public HospitalDepartment {
private:
    int numberOfPatients;

public:
    Surgery(std::string hospitalName, int numberOfDoctors, int numberOfPatients);
    bool isEfficient() override;
    std::string toString() override;
};

#endif //DOMAIN_H
